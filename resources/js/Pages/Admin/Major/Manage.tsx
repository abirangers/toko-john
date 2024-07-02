import React from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Major } from "@/types";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ManageMajor({ major }: { major: Major }) {
    const title = major ? "Edit Major" : "Create Major";
    const description = major ? "Edit major" : "Add a new major";

    const form = useForm({
        name: major?.name ?? "",
        short_name: major?.short_name ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (major) {
            form.patch(route("admin.majors.update", major?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.majors.store"), {
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
            <form onSubmit={handleSubmit} className="mt-4">
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
                <div className="mb-4">
                    <Label htmlFor="short_name">
                        Short Name<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="short_name"
                        name="short_name"
                        value={form.data.short_name}
                        onChange={(e) =>
                            form.setData("short_name", e.target.value)
                        }
                        className="mt-2"
                    />
                    {form.errors.short_name && (
                        <div className="text-red-600">
                            {form.errors.short_name}
                        </div>
                    )}
                </div>

                <Button
                    type="submit"
                    className="rounded-md"
                    disabled={form.processing}
                >
                    {major ? "Update Major" : "Add Major"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
