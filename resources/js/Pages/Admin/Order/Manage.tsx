import React from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Order, OrderStatus } from "@/types";
import { toast } from "sonner";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

export default function ManageOrder({ order }: { order: Order }) {
    const title = order ? "Edit Order" : "Create Order";
    const description = order ? "Edit order" : "Add a new order";

    const form = useForm({
        status: order?.status ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (order) {
            form.patch(route("admin.orders.update", order?.id), {
                onError: (error: any) => {
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.categories.store"), {
                onError: (error: any) => {
                    console.log(error);
                },
            });
        }
    };

    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">{title}</h1>
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            <form onSubmit={handleSubmit} className="mt-4 text-secondary">
                <div className="mb-4">
                    <Label htmlFor="role">Role</Label>
                    <Select
                        value={`${form.data.status}`}
                        onValueChange={(value) =>
                            form.setData("status", value as OrderStatus)
                        }
                    >
                        <SelectTrigger className="mt-2 w-80">
                            <SelectValue placeholder="Select Role" />
                        </SelectTrigger>
                        <SelectContent>
                            {["pending", "paid", "cancelled"].map(
                                (role) => (
                                    <SelectItem key={role} value={role}>
                                        {role}
                                    </SelectItem>
                                )
                            )}
                        </SelectContent>
                    </Select>
                </div>
                <Button
                    type="submit"
                    disabled={form.processing}
                >
                    {order ? "Update Order" : "Add Order"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
