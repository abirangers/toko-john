import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/Components/ui/separator";
import { Order } from "@/types";
import { ShoppingBag } from "lucide-react";
import OrderCard from "./OrderCard";
import { formatPrice } from "@/lib/utils";

type BadgeVariant =
    | "default"
    | "destructive"
    | "outline"
    | "secondary"
    | "highlighted";

const statusOrderVariantBadge: Record<Order["status"], BadgeVariant> = {
    pending: "secondary",
    canceled: "destructive",
    completed: "highlighted",
};

/**
 * Renders the OrderList component with the provided order details.
 *
 * @param {Order} order - The order object containing order details.
 * @return {React.JSX.Element} The rendered OrderList component.
 */
const OrderList = ({ order }: { order: Order }): React.JSX.Element => {
    /**
     * Returns the variant of the badge based on the given order status.
     *
     * @param {Order["status"]} status - The status of the order.
     * @return {BadgeVariant} The variant of the badge.
     */
    const getBadgeVariant = (status: Order["status"]): BadgeVariant =>
        statusOrderVariantBadge[status] || "default";

    return (
        <div className="p-3 space-y-4 transition-all duration-300 border shadow-md sm:py-4 sm:px-6 hover:shadow-lg rounded-xl">
            <div className="flex justify-between">
                <div className="flex items-center">
                    <ShoppingBag className="w-4 h-4 mr-2" />
                    Shopping
                </div>

                <div>
                    <Badge
                        variant={getBadgeVariant(order.status)}
                        className="capitalize"
                    >
                        {order.status}
                    </Badge>
                </div>
            </div>
            <Separator />
            <div className="space-y-3">
                {order.order_items.map((item) => {
                    return <OrderCard key={item.id} product={item.product} />;
                })}
            </div>
            <Separator />
            <div className="flex justify-between font-semibold text-black sm:text-lg">
                <h2>Total:</h2>
                {formatPrice(order.total_price)}
            </div>
        </div>
    );
};

export default OrderList;
