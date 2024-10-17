import { DataTable } from "@/Components/DataTable/DataTable";
import { Button } from "@/Components/ui/button";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Order } from "@/types";
import { router } from "@inertiajs/react";
import { Plus } from "lucide-react";
import React, { useMemo } from "react";
import { columns as columnDefs } from "./DataTable/Columns";

const IndexOrder = ({ orders }: { orders: Order[] }) => {
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Order ({orders.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Order is a list of orders that are available in the
                        store.
                    </p>
                </div>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={orders}
                    bulkDeleteEndpoint={route("admin.orders.bulkDestroy")}
                />
            </div>
        </DashboardLayout>
    );
};

export default IndexOrder;
