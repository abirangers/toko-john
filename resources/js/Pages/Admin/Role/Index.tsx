import { DataTable } from "@/Components/DataTable/DataTable";
import { Button } from "@/Components/ui/button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Category, Major, Role } from "@/types";
import { router } from "@inertiajs/react";
import { Plus } from "lucide-react";
import React, { useMemo } from "react";
import { columns as columnDefs } from "./DataTable/Columns";

const IndexCategory = ({ roles }: { roles: Role[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Role ({roles.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Role is a list of roles that are available in the
                        store.
                    </p>
                </div>
                <Button
                    className="rounded-md"
                    size="sm"
                    onClick={() => router.get(route("admin.roles.create"))}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={roles}
                    bulkDeleteEndpoint={route("admin.roles.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexCategory;
