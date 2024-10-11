import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, usePage } from "@inertiajs/react";
import { PageProps } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";
import { Button } from "@/Components/ui/button";
import Chart from "@/Components/Chart/Chart";

export default function Dashboard({ auth }: PageProps) {
    const {
        totalUser,
        totalProduct,
        totalCategory,
        totalOrder,
        ordersPerMonth,
        selectedYear,
    } = usePage().props as unknown as {
        totalUser: number;
        totalProduct: number;
        totalCategory: number;
        totalOrder: number;
        ordersPerMonth: {
            month: string;
            total: number;
        }[];
        selectedYear: number;
    };
    return (
        <DashboardLayout>
            <Head title="Dashboard" />
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Dashboard</h1>
                    <p className="text-sm text-muted-foreground">
                        Hi Admin👋, Welcome to the dashboard
                    </p>
                </div>
            </div>
            <div className="mt-4">
                <div>
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div className="p-4 bg-white rounded-lg shadow">
                            <h2 className="text-sm font-medium text-muted-foreground">
                                Total User
                            </h2>
                            <p className="mt-2 text-2xl font-bold">
                                {totalUser ?? 0}
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                No change
                            </p>
                        </div>
                        <div className="p-4 bg-white rounded-lg shadow">
                            <h2 className="text-sm font-medium text-muted-foreground">
                                Total Category
                            </h2>
                            <p className="mt-2 text-2xl font-bold">
                                {totalCategory ?? 0}
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                No change
                            </p>
                        </div>
                        <div className="p-4 bg-white rounded-lg shadow">
                            <h2 className="text-sm font-medium text-muted-foreground">
                                Total Product
                            </h2>
                            <p className="mt-2 text-2xl font-bold">
                                {totalProduct ?? 0}
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                No change
                            </p>
                        </div>
                        <div className="p-4 bg-white rounded-lg shadow">
                            <h2 className="text-sm font-medium text-muted-foreground">
                                Total Order
                            </h2>
                            <p className="mt-2 text-2xl font-bold">
                                {totalOrder ?? 0}
                            </p>
                            <p className="mt-1 text-sm text-muted-foreground">
                                No change
                            </p>
                        </div>
                    </div>
                    <div className="mt-4">
                        <Chart
                            initialOrdersPerMonth={ordersPerMonth}
                            initialSelectedYear={selectedYear}
                        />
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
