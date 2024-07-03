import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Role, User } from "@/types";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Eye, EyeOff } from "lucide-react";

export default function ManageUser({
    user,
    roles,
}: {
    user: User;
    roles: Role[];
}) {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const title = user ? "Edit User" : "Create User";
    const description = user ? "Edit user" : "Add a new user";

    const form = useForm({
        name: user?.name ?? "",
        email: user?.email ?? "",
        password: "",
        confirm_password: "",
        role: user?.role ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (user) {
            form.patch(route("admin.users.update", user?.id), {
                onError: (errors: any) => {
                    Object.values(errors).map((error: any) => {
                        toast.error(`${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.users.store"), {
                onError: (errors: any) => {
                    Object.values(errors).map((error: any) => {
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
                    <div className="relative">
                        <Input
                            type={showPassword ? "text" : "password"}
                            id="password"
                            name="password"
                            value={form.data.password}
                            onChange={(e) =>
                                form.setData("password", e.target.value)
                            }
                            className="mt-2"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5"
                        >
                            {showPassword ? (
                                <EyeOff className="w-5 h-5" />
                            ) : (
                                <Eye className="w-5 h-5" />
                            )}
                        </button>
                    </div>
                    {form.errors.password && (
                        <div className="text-red-600">
                            {form.errors.password}
                        </div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="confirm_password">Confirm Password</Label>
                    <div className="relative">
                        <Input
                            type={showConfirmPassword ? "text" : "password"}
                            id="confirm_password"
                            name="confirm_password"
                            value={form.data.confirm_password}
                            onChange={(e) =>
                                form.setData("confirm_password", e.target.value)
                            }
                            className="mt-2"
                        />
                        <button
                            type="button"
                            onClick={() =>
                                setShowConfirmPassword(!showConfirmPassword)
                            }
                            className="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5"
                        >
                            {showConfirmPassword ? (
                                <EyeOff className="w-5 h-5" />
                            ) : (
                                <Eye className="w-5 h-5" />
                            )}
                        </button>
                        {form.errors.confirm_password && (
                            <div className="text-red-600">
                                {form.errors.confirm_password}
                            </div>
                        )}
                    </div>
                </div>
                <div className="mb-4">
                    <Label htmlFor="role">Role</Label>
                    <Select
                        value={form.data.role}
                        onValueChange={(value) => form.setData("role", value)}
                    >
                        <SelectTrigger className="mt-2 w-80">
                            <SelectValue placeholder="Select Role" />
                        </SelectTrigger>
                        <SelectContent>
                            {roles.map((role) => (
                                <SelectItem key={role.id} value={role.name}>
                                    {role.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
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
