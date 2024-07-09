import React from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Category } from "@/types";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ManageCategory({ category }: { category: Category }) {
    const title = category ? "Edit Category" : "Create Category";
    const description = category ? "Edit category" : "Add a new category";

    const form = useForm({
        name: category?.name ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (category) {
            form.patch(route("admin.categories.update", category?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.categories.store"), {
                onError: (error: any) => {
                    console.log(error);
                },
            });
        }
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
                <div className="mb-4">
                    <Label htmlFor="name">
                        Name<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="name"
                        name="name"
                        value={form.data.name}
                        onChange={(e) => form.setData("name", e.target.value)}
                        className="mt-2"
                    />
                    {form.errors.name && (
                        <div className="text-red-600">{form.errors.name}</div>
                    )}
                </div>
                <Button
                    type="submit"
                    disabled={form.processing}
                >
                    {category ? "Update Category" : "Add Category"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
