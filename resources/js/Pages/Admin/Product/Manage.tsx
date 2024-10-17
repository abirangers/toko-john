import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Category, Product } from "@/types";
import { toast } from "sonner";
import MediaLibrary from "@/Components/MediaLibrary";
import { Image } from "lucide-react";
import { Textarea } from "@/Components/ui/textarea";

export default function ManageProduct({
    product,
    categories,
}: {
    product: Product;
    categories: Category[];
}) {
    const title = product ? "Edit Product" : "Create Product";
    const description = product ? "Edit product" : "Add a new product";

    const form = useForm<{
        title: string;
        description?: string;
        price: string;
        stock: string;
        image: string;
        category_id: string;
    }>({
        title: product?.title ?? "",
        description: product?.description ?? "",
        price: product?.price.toString() ?? "",
        stock: product?.stock.toString() ?? "",
        image: product?.image ?? "",
        category_id: product?.category?.id.toString() ?? "",
    });

    const [mediaLibraryOpen, setMediaLibraryOpen] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (product) {
            form.patch(route("admin.products.update", product?.id), {
                onError: (error: any) => {
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.products.store"), {
                onError: (error: any) => {
                    console.log(error);
                },
            });
        }
    };

    const handleConfirmMedia = (selectedMedia: string | string[]) => {
        form.setData("image", selectedMedia as string);
        setMediaLibraryOpen(false);
    };

    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">{title}</h1>
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            <form onSubmit={handleSubmit} className="mt-4 text-secondary">
                <div className="flex mb-4 gap-x-2">
                    <div className="w-full">
                        <Label htmlFor="title">
                            Title<span className="text-red-600">*</span>
                        </Label>
                        <Input
                            id="title"
                            name="title"
                            value={form.data.title}
                            onChange={(e) =>
                                form.setData("title", e.target.value)
                            }
                            className="mt-2"
                        />
                        {form.errors.title && (
                            <div className="text-red-600">
                                {form.errors.title}
                            </div>
                        )}
                    </div>
                    <div className="w-full">
                        <Label htmlFor="stock">
                            Stock<span className="text-red-600">*</span>
                        </Label>
                        <Input
                            id="stock"
                            name="stock"
                            value={form.data.stock}
                            onChange={(e) =>
                                form.setData("stock", e.target.value)
                            }
                            className="mt-2"
                        />
                        {form.errors.stock && (
                            <div className="text-red-600">
                                {form.errors.stock}
                            </div>
                        )}
                    </div>
                </div>
                <div className="flex mb-4 gap-x-2">
                    <div className="w-full">
                        <Label htmlFor="price">
                            Price<span className="text-red-600">*</span>
                        </Label>
                        <Input
                            id="price"
                            name="price"
                            value={form.data.price}
                            onChange={(e) =>
                                form.setData("price", e.target.value)
                            }
                            className="mt-2"
                        />
                        {form.errors.price && (
                            <div className="text-red-600">
                                {form.errors.price}
                            </div>
                        )}
                    </div>
                    <div className="w-full">
                        <Label htmlFor="category_id">
                            Category<span className="text-red-600">*</span>
                        </Label>
                        <Select
                            value={form.data.category_id}
                            onValueChange={(value) =>
                                form.setData("category_id", value)
                            }
                        >
                            <SelectTrigger className="mt-2">
                                <SelectValue placeholder="Select Category" />
                            </SelectTrigger>
                            <SelectContent>
                                {categories.map((category) => (
                                    <SelectItem
                                        key={category.id}
                                        value={category.id.toString()}
                                    >
                                        {category.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {form.errors.category_id && (
                            <div className="text-red-600">
                                {form.errors.category_id}
                            </div>
                        )}
                    </div>
                </div>

                <div className="mb-4">
                    <Label htmlFor="description" className="mb-2">
                        Description
                    </Label>
                    <Textarea
                        id="description"
                        name="description"
                        value={form.data.description ?? ""}
                        onChange={(e) =>
                            form.setData("description", e.target.value)
                        }
                        className="mt-2"
                        placeholder="Enter description"
                    />
                    {form.errors.description && (
                        <div className="text-red-600">
                            {form.errors.description}
                        </div>
                    )}
                </div>
                <div className="flex flex-col mb-4">
                    <Label htmlFor="media">
                        Image<span className="text-red-600">*</span>
                    </Label>
                    <Button
                        variant="outline"
                        className="mt-2 gap-x-1"
                        onClick={(e) => {
                            e.preventDefault();
                            setMediaLibraryOpen(true);
                        }}
                    >
                        <Image className="w-5 h-5" />
                        {form.data.image ? "Change Image" : "Upload Image"}
                    </Button>
                    {form.errors.image && (
                        <div className="text-red-600">{form.errors.image}</div>
                    )}
                </div>
                {form.data.image && (
                    <div className="mb-4">
                        <Label>Image Preview</Label>
                        <div className="mt-2 overflow-hidden border border-gray-300 rounded-lg">
                            <img
                                src={form.data.image}
                                alt="Image Preview"
                                className="object-cover w-full h-auto aspect-video"
                            />
                        </div>
                    </div>
                )}

                <Button
                    type="submit"
                    disabled={form.processing}
                >
                    {product ? "Update Product" : "Add Product"}
                </Button>
            </form>

            <MediaLibrary
                multiple={false}
                onConfirm={handleConfirmMedia}
                open={mediaLibraryOpen}
                onClose={() => setMediaLibraryOpen(false)}
            />
        </DashboardLayout>
    );
}
