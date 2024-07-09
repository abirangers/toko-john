import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Role } from "@/types";
import { toast } from "sonner";
import slugify from "slugify";

export default function ManageRole({ role }: { role: Role }) {
    const title = role ? "Edit Role" : "Create Role";
    const description = role ? "Edit role" : "Add a new role";

    const form = useForm({
        name: role?.name ?? "",
        display_name: role?.display_name ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (role) {
            form.patch(route("admin.roles.update", role?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.roles.store"), {
                onError: (error: any) => {
                    console.log(error);
                },
            });
        }
    };

    useEffect(() => {
        if (form.data.display_name) {
            form.setData(
                "name",
                slugify(form.data.display_name, { lower: true })
            );
        }
    }, [form.data.display_name]);

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
                    <Label htmlFor="display_name">
                        Name<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="display_name"
                        name="display_name"
                        value={form.data.display_name}
                        onChange={(e) =>
                            form.setData("display_name", e.target.value)
                        }
                        onBlur={(e) =>
                            form.setData("display_name", e.target.value)
                        }
                        className="mt-2"
                    />
                    {form.errors.display_name && (
                        <div className="text-red-600">
                            {form.errors.display_name}
                        </div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="name">
                        Key<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="name"
                        name="name"
                        value={form.data.name}
                        onChange={(e) => form.setData("name", e.target.value)}
                        onBlur={(e) => form.setData("name", e.target.value)}
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
                    {role ? "Update Role" : "Add Role"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
