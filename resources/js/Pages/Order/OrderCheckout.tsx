import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import MainLayout from "@/Layouts/MainLayout";
import { Cart, Province, Regency, District, Village } from "@/types";
import { Link, useForm, router } from "@inertiajs/react";
import { ExternalLink } from "lucide-react";
import React, { useEffect, useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import {
    getProvinces,
    getRegencies,
    getDistricts,
    getVillages,
} from "@/services/region.services";
import { toast } from "sonner";
import { ComboBoxResponsive } from "@/Components/ComboBoxResponsive";

interface OrderCheckoutProps {
    cart: Cart;
}

const OrderCheckout: React.FC<OrderCheckoutProps> = ({ cart }) => {
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [isOrderSuccessful, setIsOrderSuccessful] = useState(false);
    const [provinces, setProvinces] = useState<Province[]>([]);
    const [regencies, setRegencies] = useState<Regency[]>([]);
    const [districts, setDistricts] = useState<District[]>([]);
    const [villages, setVillages] = useState<Village[]>([]);

    const form = useForm({
        name: cart.user.name,
        email: cart.user.email,
        cart_id: cart.id,
        province_id: "",
        regency_id: "",
        district_id: "",
        village_id: "",
        address: "",
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        try {
            form.post(route("order.store"), {
                onSuccess: (params) => {
                    console.log(params);
                    const flash = params.props.flash as {
                        error: string;
                    };

                    if (flash.error) {
                        toast.error(flash.error);
                        return;
                    }
                    
                    setIsOrderSuccessful(true);
                    setIsDialogOpen(true);
                    form.reset();
                },
                onError: (error) => {
                    console.log(error);
                    toast.error("Something went wrong");
                },
            });
        } catch (error) {
            toast.error("Something went wrong");
        }
    };

    useEffect(() => {
        if (!isDialogOpen && isOrderSuccessful) {
            router.visit(route("order.index"));
        }
        console.log(isDialogOpen);
    }, [isDialogOpen, isOrderSuccessful]);

    useEffect(() => {
        getProvinces().then((res) => {
            setProvinces(res.data);
        });
    }, []);

    useEffect(() => {
        if (form.data.province_id) {
            getRegencies(form.data.province_id).then((res) => {
                setRegencies(res.data);
                setDistricts([]);
                setVillages([]);
            });
        }
    }, [form.data.province_id]);

    useEffect(() => {
        if (form.data.regency_id) {
            getDistricts(form.data.regency_id).then((res) => {
                setDistricts(res.data);
                setVillages([]);
            });
        }
    }, [form.data.regency_id]);

    useEffect(() => {
        if (form.data.district_id) {
            getVillages(form.data.district_id).then((res) => {
                setVillages(res.data);
            });
        }
    }, [form.data.district_id]);

    return (
        <MainLayout user={cart.user}>
            <div className="container py-8 mx-auto">
                <h1 className="mb-8 text-4xl font-extrabold tracking-tight text-center">
                    Product Checkout
                </h1>
                <div className="p-8 border rounded-lg">
                    <p className="mb-4 text-sm font-bold text-gray-600">
                        Product Name
                    </p>
                    <ul className="mb-6 space-y-2">
                        {cart.cart_items.map((item) => (
                            <li
                                key={item.id}
                                className="text-lg font-medium text-gray-700 hover:text-primary w-fit hover:underline"
                            >
                                <Link
                                    href={route(
                                        "product.show",
                                        item.product.slug
                                    )}
                                    className="flex items-center gap-x-2"
                                >
                                    {item.product.title}{" "}
                                    <ExternalLink className="w-5 h-5" />
                                </Link>
                            </li>
                        ))}
                    </ul>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <Label
                                htmlFor="name"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Name
                            </Label>
                            <Input
                                id="name"
                                name="name"
                                value={form.data.name}
                                onChange={(e) =>
                                    form.setData("name", e.target.value)
                                }
                                disabled
                                className="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            />
                            {form.errors.name && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.name}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="email"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Email
                            </Label>
                            <Input
                                id="email"
                                name="email"
                                value={form.data.email}
                                onChange={(e) =>
                                    form.setData("email", e.target.value)
                                }
                                disabled
                                className="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            />
                            {form.errors.email && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.email}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="province_id"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Province
                            </Label>
                            <ComboBoxResponsive
                                items={provinces.map((province) => ({
                                    label: province.name,
                                    value: province.id,
                                }))}
                                placeholder="Pilih Provinsi"
                                value={form.data.province_id}
                                onChange={(value) => {
                                    form.setData("province_id", value);
                                }}
                            />
                            {form.errors.province_id && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.province_id}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="regency_id"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Regency
                            </Label>
                            <ComboBoxResponsive
                                items={regencies.map((regency) => ({
                                    label: regency.name,
                                    value: regency.id,
                                }))}
                                placeholder="Pilih Kota/Kabupaten"
                                value={form.data.regency_id}
                                onChange={(value) =>
                                    form.setData("regency_id", value)
                                }
                            />
                            {form.errors.regency_id && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.regency_id}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="district_id"
                                className="block text-sm font-medium text-gray-700"
                            >
                                District
                            </Label>
                            <ComboBoxResponsive
                                items={districts.map((district) => ({
                                    label: district.name,
                                    value: district.id,
                                }))}
                                placeholder="Pilih Kecamatan"
                                value={form.data.district_id}
                                onChange={(value) =>
                                    form.setData("district_id", value)
                                }
                            />
                            {form.errors.district_id && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.district_id}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="village_id"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Village
                            </Label>
                            <ComboBoxResponsive
                                items={villages.map((village) => ({
                                    label: village.name,
                                    value: village.id,
                                }))}
                                placeholder="Pilih Desa/Kelurahan"
                                value={form.data.village_id}
                                onChange={(value) =>
                                    form.setData("village_id", value)
                                }
                            />
                            {form.errors.village_id && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.village_id}
                                </p>
                            )}
                        </div>
                        <div className="mb-4">
                            <Label
                                htmlFor="address"
                                className="block text-sm font-medium text-gray-700"
                            >
                                Full Address
                            </Label>
                            <Textarea
                                id="address"
                                name="address"
                                placeholder="Masukkan alamat lengkap"
                                value={form.data.address}
                                onChange={(e) =>
                                    form.setData("address", e.target.value)
                                }
                                className="mt-2"
                            />
                            {form.errors.address && (
                                <p className="mt-2 text-sm text-red-500">
                                    {form.errors.address}
                                </p>
                            )}
                        </div>
                        <div className="flex justify-end pt-2 space-x-4">
                            <Button
                                variant="outline"
                                type="button"
                                className="px-4 py-2 text-gray-700 border border-gray-300 hover:bg-gray-50"
                                onClick={() =>
                                    router.visit(route("home.index"))
                                }
                            >
                                Cancel
                            </Button>
                            <Button
                                type="submit"
                                className="px-4 py-2 text-white rounded-md"
                            >
                                Order Now
                            </Button>
                        </div>
                    </form>
                </div>
            </div>

            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle className="flex justify-center">
                            <video className="outline-none" autoPlay>
                                <source
                                    src="/anim/animation.mp4"
                                    type="video/mp4"
                                    className={`border-none`}
                                />
                                Your browser does not support the video tag
                            </video>
                        </DialogTitle>
                        <DialogDescription className="text-base text-center">
                            <h2 className="mb-2 text-xl font-semibold text-gray-800">
                                Order Successful!
                            </h2>
                            <p className="mb-4 text-gray-600">
                                Thank you! Your invoice has been created. Please
                                check your email to continue with the payment.
                            </p>
                        </DialogDescription>
                    </DialogHeader>
                </DialogContent>
            </Dialog>
        </MainLayout>
    );
};

export default OrderCheckout;
