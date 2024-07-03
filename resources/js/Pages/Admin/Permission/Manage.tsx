import React, { useEffect } from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Permission, PermissionGroup } from "@/types";
import { toast } from "sonner";
import slugify from "slugify";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

export default function ManagePermission({
    permission,
    permissionGroups,
}: {
    permission: Permission;
    permissionGroups: PermissionGroup[];
}) {
    const title = permission ? "Edit Permission" : "Create Permission";
    const description = permission ? "Edit permission" : "Add a new permission";

    const form = useForm({
        name: permission?.name ?? "",
        display_name: permission?.display_name ?? "",
        group_name: permission?.group_name ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (permission) {
            form.patch(route("admin.permissions.update", permission?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(error);
                    });
                },
            });
        } else {
            form.post(route("admin.permissions.store"), {
                onError: (error: any) => {
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(error);
                    });
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
                <div className="mb-4">
                    <Label htmlFor="group_name">Group</Label>
                    <Select
                        value={form.data.group_name}
                        onValueChange={(value) =>
                            form.setData("group_name", value)
                        }
                    >
                        <SelectTrigger className="mt-2">
                            <SelectValue placeholder="Select a group" />
                        </SelectTrigger>
                        <SelectContent>
                            {permissionGroups.map((group) => (
                                <SelectItem key={group.id} value={group.name}>
                                    {group.name}
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
                    {permission ? "Update Permission" : "Add Permission"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
