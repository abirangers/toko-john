import DashboardLayout from "@/Layouts/DashboardLayout";
import { Checkbox } from "@/Components/ui/checkbox";
import { router, useForm } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Pencil } from "lucide-react";
import { Permission, PermissionGroup, Role } from "@/types";
import { toast } from "sonner";

export default function ShowRole({
    role,
    rolePermissions,
    permissionGroups,
}: {
    role: Role;
    rolePermissions: string[];
    permissionGroups: PermissionGroup[];
}) {
    const {
        data: { permissions: selectedPermissions },
        setData,
        put,
        processing,
    } = useForm({ permissions: rolePermissions, fromShowPage: true });

    const handlePermissionChange = (permission: Permission) => {
        const permissionName = permission.name;
        if (selectedPermissions.includes(permissionName)) {
            setData(
                "permissions",
                selectedPermissions.filter((name) => name !== permissionName)
            );
        } else {
            setData("permissions", [...selectedPermissions, permissionName]);
        }
    };

    const handleSelectAllChange = (checked: boolean) => {
        if (checked) {
            const allPermissionNames = permissionGroups.flatMap(
                (group) => group.permissions.map((p) => p.name)
            );
            setData("permissions", allPermissionNames);
        } else {
            setData("permissions", []);
        }
    };

    const handleSave = () => {
        put(route("admin.roles.update", role.id), {
            preserveScroll: true,
            data: { permissions: selectedPermissions, fromShowPage: true },
            onError: (e) => {
                console.log(e);
                toast.error("failed to update role permissions");
            },
        });
    };

    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Role</h1>
                    <p className="text-sm text-muted-foreground">
                        {role.display_name}
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() =>
                        router.get(route("admin.roles.edit", role.id))
                    }
                >
                    <Pencil className="w-3 h-3 mr-2" />
                    Edit
                </Button>
            </div>
            <div className="mt-4">
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Display Name
                    </label>
                    <input
                        type="text"
                        value={role.display_name}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Name
                    </label>
                    <input
                        type="text"
                        value={role.name}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mt-8">
                    <h2 className="mb-4 text-xl font-semibold">
                        Manage Role Permissions
                    </h2>
                    <div className="p-6 bg-white rounded-lg shadow-sm border-[1.5px]">
                        <div className="flex items-center justify-between pb-4 border-b border-base-200">
                            <h3 className="text-lg font-medium">
                                Manage Role Permissions
                            </h3>
                            <Button
                                size="sm"
                                onClick={() => handleSave()}
                            >
                                Save
                            </Button>
                        </div>
                        <div className="flex items-center gap-2 pt-4">
                            <Checkbox
                                onCheckedChange={handleSelectAllChange}
                                checked={
                                    selectedPermissions.length ===
                                    permissionGroups.flatMap(
                                        (group) => group.permissions
                                    ).length
                                }
                                id="select-all"
                            />
                            <label
                                className="cursor-pointer"
                                htmlFor="select-all"
                            >
                                Select All
                            </label>
                        </div>
                        <div className="grid grid-cols-1 gap-4 pt-4 md:grid-cols-2 lg:grid-cols-3">
                            {permissionGroups.map((permissionGroup, index) => (
                                <div
                                    key={`permission-group-${index}`}
                                    className={
                                        permissionGroup.permissions.length > 0
                                            ? "block"
                                            : "hidden"
                                    }
                                >
                                    <p className="font-bold">
                                        {permissionGroup.name}
                                    </p>
                                    <ul className="grid grid-cols-1 gap-2 pt-3">
                                        {permissionGroup.permissions.map(
                                            (permission, index2) => (
                                                <li
                                                    key={`permission-${index2}`}
                                                    className="flex items-center gap-2"
                                                >
                                                    <Checkbox
                                                        onCheckedChange={() =>
                                                            handlePermissionChange(
                                                                permission
                                                            )
                                                        }
                                                        value={permission.name}
                                                        checked={selectedPermissions.includes(
                                                            permission.name
                                                        )}
                                                        
                                                        name={permission.name}
                                                        id={permission.name}
                                                    />
                                                    <label
                                                        className="cursor-pointer"
                                                        htmlFor={
                                                            permission.name
                                                        }
                                                    >
                                                        {
                                                            permission.display_name
                                                        }
                                                    </label>
                                                </li>
                                            )
                                        )}
                                    </ul>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}