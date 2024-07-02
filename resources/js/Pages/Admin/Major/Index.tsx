import { DataTable } from "@/Components/DataTable/DataTable";
import { Button } from "@/Components/ui/button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Major } from "@/types";
import { router } from "@inertiajs/react";
import { Plus } from "lucide-react";
import React, { useMemo } from "react";
import { columns as columnDefs } from "./DataTable/Columns";

const IndexMajor = ({ majors }: { majors: Major[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Major ({majors.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Major is a list of majors that are available in the
                        store.
                    </p>
                </div>
                <Button
                    className="rounded-md"
                    size="sm"
                    onClick={() => router.get(route("admin.majors.create"))}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={majors}
                    bulkDeleteEndpoint={route("admin.majors.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexMajor;
