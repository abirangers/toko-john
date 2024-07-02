import React from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { User } from "@/types";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ManageUser({ user }: { user: User }) {
    const title = user ? "Edit User" : "Create User";
    const description = user ? "Edit user" : "Add a new user";

    const form = useForm({
        name: user?.name ?? "",
        email: user?.email ?? "",
        password: "",
        confirm_password: "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (user) {
            form.patch(route("admin.users.update", user?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.users.store"), {
                onError: (error: any) => {
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`${error}`);
                    });
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
                <div className="flex mb-4 gap-x-2">
                    <div className="w-full">
                        <Label htmlFor="name">
                            Name<span className="text-red-600">*</span>
                        </Label>
                        <Input
                            id="name"
                            name="name"
                            value={form.data.name}
                            onChange={(e) =>
                                form.setData("name", e.target.value)
                            }
                            className="mt-2"
                        />
                        {form.errors.name && (
                            <div className="text-red-600">
                                {form.errors.name}
                            </div>
                        )}
                    </div>
                    <div className="w-full">
                        <Label htmlFor="email">
                            Email<span className="text-red-600">*</span>
                        </Label>
                        <Input
                            id="email"
                            name="email"
                            value={form.data.email}
                            onChange={(e) =>
                                form.setData("email", e.target.value)
                            }
                            className="mt-2"
                        />
                        {form.errors.email && (
                            <div className="text-red-600">
                                {form.errors.email}
                            </div>
                        )}
                    </div>
                </div>
                <div className="mb-4">
                    <Label htmlFor="password">Password</Label>
                    <Input
                        type="password"
                        id="password"
                        name="password"
                        value={form.data.password}
                        onChange={(e) =>
                            form.setData("password", e.target.value)
                        }
                        className="mt-2"
                    />
                    {form.errors.password && (
                        <div className="text-red-600">
                            {form.errors.password}
                        </div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="confirm_password">Confirm Password</Label>
                    <Input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        value={form.data.confirm_password}
                        onChange={(e) =>
                            form.setData("confirm_password", e.target.value)
                        }
                        className="mt-2"
                    />
                    {form.errors.confirm_password && (
                        <div className="text-red-600">
                            {form.errors.confirm_password}
                        </div>
                    )}
                </div>

                <Button
                    type="submit"
                    className="rounded-md"
                    disabled={form.processing}
                >
                    {user ? "Update User" : "Add User"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
