import DashboardLayout from "@/Layouts/DashboardLayout";
import React, { useMemo } from "react";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Plus } from "lucide-react";
import { Product } from "@/types";
import { columns as columnDefs } from "./DataTable/Columns";
import { DataTable } from "@/Components/DataTable/DataTable";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

const IndexProduct = ({ products }: { products: Product[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Product ({products.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Product is a list of products that are available in the
                        store.
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() => router.get(route("admin.products.create"))}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={products}
                    bulkDeleteEndpoint={route("admin.products.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexProduct;
