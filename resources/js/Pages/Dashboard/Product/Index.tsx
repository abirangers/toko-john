import DashboardLayout from "@/Layouts/DashboardLayout";
import { User } from "@/types";
import React, { useEffect } from "react";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Link, router, usePage } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Plus } from "lucide-react";
import { Product } from "@/types";
import { DataTable } from "./DataTable/DataTable";
import { columns } from "./DataTable/Columns";
import { toast } from "sonner";

export default function IndexProduct({ products }: { products: Product[] }) {
    const {flash} = usePage().props as unknown as {flash: {success: string}};
    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }
    }, [flash]);
    return (
        <DashboardLayout>
            <Breadcrumb>
                <BreadcrumbList>
                    <BreadcrumbItem>
                        <BreadcrumbLink>
                            <Link href="/">Dashboard</Link>
                        </BreadcrumbLink>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Product</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Product ({products.length})</h1>
                    <p className="text-sm text-muted-foreground">
                        Product is a list of products that are available in the
                        store.
                    </p>
                </div>
                <Button className="rounded-md" size="sm" onClick={() => router.get(route('admin.products.create'))}>
                        <Plus className="w-5 h-5 mr-2" />
                        Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable columns={columns} data={products}/>
            </div>
        </DashboardLayout>
    );
}
