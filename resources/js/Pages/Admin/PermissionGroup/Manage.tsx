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

export default function ManagePermissionGroup({
    permissionGroup,
}: {
    permissionGroup: PermissionGroup;
}) {
    const title = permissionGroup ? "Edit Permission Group" : "Create Permission Group";
    const description = permissionGroup ? "Edit permission group" : "Add a new permission group";

    const form = useForm({
        name: permissionGroup?.name ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (permissionGroup) {
            form.patch(route("admin.permission-groups.update", permissionGroup?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(error);
                    });
                },
            });
        } else {
            form.post(route("admin.permission-groups.store"), {
                onError: (error: any) => {
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(error);
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
            <form onSubmit={handleSubmit} className="mt-4 text-secondary">
                <div className="mb-4">
                    <Label htmlFor="display_name">
                        Name<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="display_name"
                        name="display_name"
                        value={form.data.name}
                        onChange={(e) =>
                            form.setData("name", e.target.value)
                        }
                        onBlur={(e) =>
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
    
                <Button
                    type="submit"
                    disabled={form.processing}
                >
                    {permissionGroup ? "Update Permission Group" : "Add Permission Group"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
