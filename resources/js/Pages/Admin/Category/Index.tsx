import { DataTable } from "@/Components/DataTable/DataTable";
import { Button } from "@/Components/ui/button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Category } from "@/types";
import { router } from "@inertiajs/react";
import { Plus } from "lucide-react";
import React, { useMemo } from "react";
import { columns as columnDefs } from "./DataTable/Columns";

const IndexCategory = ({ categories }: { categories: Category[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Category ({categories.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Category is a list of categories that are available in the
                        store.
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() => router.get(route("admin.categories.create"))}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={categories}
                    bulkDeleteEndpoint={route("admin.categories.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexCategory;
