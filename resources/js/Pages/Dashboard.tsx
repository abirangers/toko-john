import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { PageProps } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";
import { Button } from "@/Components/ui/button";

export default function Dashboard({ auth }: PageProps) {
    return (
        <DashboardLayout>
            <Head title="Dashboard" />
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Dashboard</h1>
                    <p className="text-sm text-muted-foreground">
                        Welcome to the dashboard
                    </p>
                </div>
            </div>
            <div className="mt-4"></div>
        </DashboardLayout>
    );
}
