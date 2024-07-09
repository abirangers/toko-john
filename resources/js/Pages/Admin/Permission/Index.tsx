import { DataTable } from "@/Components/DataTable/DataTable";
import { Button } from "@/Components/ui/button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Permission } from "@/types";
import { router } from "@inertiajs/react";
import { Plus } from "lucide-react";
import React, { useMemo } from "react";
import { columns as columnDefs } from "./DataTable/Columns";

const IndexPermission = ({ permissions }: { permissions: Permission[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Permission ({permissions.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Permission is a list of permissions that are available in the
                        store.
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() => router.get(route("admin.permissions.create"))}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={permissions}
                    bulkDeleteEndpoint={route("admin.permissions.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexPermission;
