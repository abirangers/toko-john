import DashboardLayout from "@/Layouts/DashboardLayout";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { Link, router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Pencil, Plus } from "lucide-react";
import { Category } from "@/types";
import { columns } from "./DataTable/Columns";
import { DataTable } from "@/Components/DataTable/DataTable";
import { formatPrice, getMediaUrl } from "@/lib/utils";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ShowCategory({ category }: { category: Category }) {
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Category</h1>
                    <p className="text-sm text-muted-foreground">
                        {category.name}
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() =>
                        router.get(route("admin.categories.edit", category.id))
                    }
                >
                    <Pencil className="w-3 h-3 mr-2" />
                    Edit
                </Button>
            </div>
            <div className="mt-4 text-secondary">
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Category Name
                    </label>
                    <input
                        type="text"
                        value={category.name}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-7000">
                        Slug
                    </label>
                    <input
                        type="text"
                        value={category.slug}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
