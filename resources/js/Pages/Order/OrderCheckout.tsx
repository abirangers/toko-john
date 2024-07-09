import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import MainLayout from "@/Layouts/MainLayout";
import { Order } from "@/types";
import { Link, router, useForm } from "@inertiajs/react";
import { ExternalLink } from "lucide-react";
import React from "react";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { useState, useEffect } from "react";
import { toast } from "sonner";

interface OrderCheckoutProps {
    order: Order;
}

const OrderCheckout: React.FC<OrderCheckoutProps> = ({ order }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [isOrderSuccessful, setIsOrderSuccessful] = useState(false);

    const form = useForm({
        name: order?.user?.name ?? "",
        email: order?.user?.email ?? "",
    });

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        try {
            form.post(route("order.store", order.id), {
                onSuccess: (params) => {
                    const flash = params.props.flash as {
                        error: string;
                    };

                    if (flash.error) {
                        toast.error(flash.error);
                        return;
                    }

                    setIsOrderSuccessful(true);
                    setIsOpen(true);
                },
            });
        } catch (error) {
            toast.error("Something went wrong");
        }
    };

    useEffect(() => {
        if (!isOpen && isOrderSuccessful) {
            router.visit(route("order.index"));
        }
    }, [isOpen, isOrderSuccessful]);

    return (
        <MainLayout user={order.user}>
            <div className="container py-8 mx-auto">
                <h1 className="mb-6 text-4xl font-extrabold tracking-tight text-center">
                    Product Checkout
                </h1>
                <div className="p-8 border rounded-lg">
                    <p className="mb-4 text-sm font-bold text-gray-600">
                        Product Name
                    </p>
                    <ul className="mb-6 space-y-2">
                        {order.order_items.map((item) => (
                            <li
                                key={item.id}
                                className="text-lg font-medium text-gray-700 hover:text-primary"
                            >
                                <Link
                                    href={route("product.show", item.product.slug)}
                                    className="flex items-center gap-x-2"
                                >
                                    {item.product.title}{" "}
                                    <ExternalLink className="w-5 h-5" />
                                </Link>
                            </li>
                        ))}
                    </ul>
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div>
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
                                className="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            />
                        </div>
                        <div>
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
                                className="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            />
                        </div>
                        <div className="flex justify-end space-x-4">
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

            <Dialog open={isOpen} onOpenChange={setIsOpen}>
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
