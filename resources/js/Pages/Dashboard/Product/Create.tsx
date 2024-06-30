import React, { useState, useEffect } from "react";
import { Link, useForm } from "@inertiajs/react";
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
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Category, Product } from "@/types";
import { toast } from "sonner";

export default function ManageProduct({
    product,
    categories,
}: {
    product: Product;
    categories: Category[];
}) {
    const title = product ? "Edit Product" : "Create Product";
    const description = product ? "Edit product" : "Add a new product";

    const form = useForm({
        title: product?.title ?? "",
        description: product?.description ?? "",
        price: product?.price.toString() ?? "",
        stock: product?.stock.toString() ?? "",
        image: product?.image ?? (null as File | null),
        category_id: product?.category?.id.toString() ?? "",
    });

    const [imagePreview, setImagePreview] = useState<string | null>(null);

    useEffect(() => {
        if (product?.image) {
            setImagePreview(`/images/products/${product.image}`);
        }
    }, [product]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (product) {
            form.patch(route("admin.products.update", product?.id), {
                forceFormData: true,
                onError: (error: any) => {
                    console.log(error);
                    Object.values(error).map((error: any) => {
                        toast.error(error);
                    });
                },
            });
        } else {
            form.post(route("admin.products.store"), {
                forceFormData: true,
                onError: (error) => {
                    console.log(error);
                    Object.values(error).map((error) => {
                        toast.error(error);
                    });
                },
            });
        }
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e?.target?.files?.[0] || null;
        form.setData("image", file as unknown as string);
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setImagePreview(reader.result as string | null);
            };
            reader.readAsDataURL(file);
        } else {
            setImagePreview(null);
        }
    };

    return (
        <DashboardLayout>
            <Breadcrumb>
                <BreadcrumbList>
                    <BreadcrumbItem>
                        <BreadcrumbLink>
                            <Link href="/">Dashboard</Link>
                        </BreadcrumbLink>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator />
                    <BreadcrumbItem>
                        <BreadcrumbLink>
                            <Link href={route("admin.products.index")}>
                                Product
                            </Link>
                        </BreadcrumbLink>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator />
                    <BreadcrumbItem>
                        <BreadcrumbPage>{title}</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">{title}</h1>
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            <form onSubmit={handleSubmit} className="mt-4">
                <div className="mb-4">
                    <Label htmlFor="title">Title</Label>
                    <Input
                        id="title"
                        name="title"
                        value={form.data.title}
                        onChange={(e) => form.setData("title", e.target.value)}
                        className="mt-2"
                    />
                    {form.errors.title && (
                        <div className="text-red-600">{form.errors.title}</div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="price">Price</Label>
                    <Input
                        id="price"
                        name="price"
                        value={form.data.price}
                        onChange={(e) => form.setData("price", e.target.value)}
                        className="mt-2"
                    />
                    {form.errors.price && (
                        <div className="text-red-600">{form.errors.price}</div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="stock">Stock</Label>
                    <Input
                        id="stock"
                        name="stock"
                        value={form.data.stock}
                        onChange={(e) => form.setData("stock", e.target.value)}
                        className="mt-2"
                    />
                </div>
                <div className="mb-4">
                    <Label htmlFor="description" className="mb-2">
                        Description
                    </Label>
                    <Input
                        id="description"
                        name="description"
                        value={form.data.description}
                        onChange={(e) =>
                            form.setData("description", e.target.value)
                        }
                        className="mt-2"
                    />
                    {form.errors.description && (
                        <div className="text-red-600">
                            {form.errors.description}
                        </div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="category_id">Category</Label>
                    <Select
                        value={form.data.category_id}
                        onValueChange={(value) =>
                            form.setData("category_id", value)
                        }
                    >
                        <SelectTrigger className="w-[180px] mt-2">
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
                </div>
                <div className="mb-4">
                    <Label htmlFor="image">Image</Label>
                    <Input
                        id="image"
                        name="image"
                        type="file"
                        onChange={handleFileChange}
                        className="mt-2"
                    />
                    {form.errors.image && (
                        <div className="text-red-600">{form.errors.image}</div>
                    )}
                </div>
                {imagePreview && (
                    <div className="mb-4">
                        <Label>Image Preview</Label>
                        <img
                            src={imagePreview}
                            alt="Image Preview"
                            className="mt-2 w-52"
                        />
                    </div>
                )}
                <Button
                    type="submit"
                    className="rounded-md"
                    disabled={form.processing}
                >
                    {product ? "Update Product" : "Add Product"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
