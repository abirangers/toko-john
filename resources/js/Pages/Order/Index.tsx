import Navbar from "@/Components/Navbar/Navbar";
import { Separator } from "@/Components/ui/separator";
import { Order, PageProps } from "@/types";
import { usePage } from "@inertiajs/react";
import OrderList from "./OrderList";
import Footer from "@/Components/Footer/Footer";
import OrderTabs from "./OrderTabs";
import { formatPrice } from "@/lib/utils";
import MainLayout from "@/Layouts/MainLayout";
import { useEffect } from "react";
import { toast } from "sonner";

const OrderPage = ({
    auth,
    orders,
    totalOrder,
}: PageProps<{ orders: Order[]; totalOrder: number }>) => {
    const { flash } = usePage().props as unknown as {
        flash: { success: string };
    };
    useEffect(() => {
        if (flash.success) {
            toast.success(flash.success);
        }
    }, [flash]);
    return (
        <MainLayout user={auth.user}>
            <section className="max-w-5xl px-8 pt-10 mx-auto">
                <div className="mb-6">
                    <h2 className="mb-1 text-3xl font-bold tracking-tight">
                        Orders
                    </h2>
                    <p className="mb-4 text-sm font-normal text-muted-foreground">
                        See Your Transaction History
                    </p>
                </div>

                <div className="p-4 mb-8 bg-gray-100 rounded">
                    <h3 className="text-xl font-semibold text-secondary">
                        Total Order:
                    </h3>
                    <p className="text-2xl font-bold text-primary">
                        {formatPrice(totalOrder)}
                    </p>
                </div>

                <OrderTabs />
                <Separator />

                <div className="mt-8 space-y-5">
                    {orders.length > 0 ? (
                        orders.map((order, i) => (
                            <OrderList key={i} order={order} />
                        ))
                    ) : (
                        <div className="p-4 text-center bg-gray-100 rounded">
                            <p className="text-lg font-medium text-gray-700">
                                No orders found
                            </p>
                        </div>
                    )}
                </div>
            </section>
        </MainLayout>
    );
};
export default OrderPage;
