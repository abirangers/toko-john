import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/Components/ui/separator";
import { Order } from "@/types";
import {
    CreditCard,
    Eye,
    MoreVertical,
    ShoppingBag,
    XCircleIcon,
} from "lucide-react";
import OrderCard from "./OrderCard";
import { useState } from "react";

import { formatPrice } from "@/lib/utils";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { ConfirmDialog } from "@/Components/ConfirmDialog";

type BadgeVariant =
    | "default"
    | "destructive"
    | "outline"
    | "secondary"
    | "highlighted";

const statusOrderVariantBadge: Record<Order["status"], BadgeVariant> = {
    pending: "secondary",
    cancelled: "destructive",
    paid: "highlighted",
};

const OrderList = ({ order }: { order: Order }): React.JSX.Element => {
    const [open, setOpen] = useState(false);
    const [deleting, setDeleting] = useState(false);

    const getBadgeVariant = (status: Order["status"]): BadgeVariant =>
        statusOrderVariantBadge[status] || "default";

    const handleDelete = () => {
        setDeleting(true);
        router.delete(route("order.destroy", order.id), {
            onSuccess: () => {
                setOpen(false);
                setDeleting(false);
            },
            onError: () => {
                setDeleting(false);
            },
            onFinish: () => {
                setOpen(false);
                setDeleting(false);
            },
        });
    };

    return (
        <div className="p-3 space-y-4 transition-all duration-300 border shadow-md sm:py-4 sm:px-6 hover:shadow-lg rounded-xl">
            <div className="flex justify-between">
                <div className="flex items-center font-semibold">
                    <ShoppingBag className="w-4 h-4 mr-2" />
                    Shopping
                </div>

                <div className="flex items-center gap-x-2">
                    <Badge
                        variant={getBadgeVariant(order.status)}
                        className="capitalize"
                    >
                        {order.status}
                    </Badge>
                    <DropdownMenu modal={false}>
                        <DropdownMenuTrigger asChild>
                            <Button variant="ghost" className="w-8 h-8 p-0">
                                <span className="sr-only">Open menu</span>
                                <MoreVertical className="w-4 h-4" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>Actions</DropdownMenuLabel>
                            {order.status === "pending" && (
                                <DropdownMenuItem
                                    onClick={() =>
                                        router.get(
                                            route("order.create", order.id)
                                        )
                                    }
                                    className="cursor-pointer"
                                >
                                    <CreditCard className="w-4 h-4 mr-2" />
                                    Complete
                                </DropdownMenuItem>
                            )}
                            {order.status === "pending" ||
                                (order.status === "paid" && (
                                    <DropdownMenuItem
                                        onClick={() =>
                                            router.get(
                                                route("order.show", order.id)
                                            )
                                        }
                                        className="cursor-pointer"
                                    >
                                        <Eye className="w-4 h-4 mr-2" />
                                        View Details
                                    </DropdownMenuItem>
                                ))}

                            {order.status === "pending" ||
                                (order.status === "cancelled" && (
                                    <DropdownMenuItem
                                        onClick={() => setOpen(true)}
                                        className="cursor-pointer"
                                    >
                                        <XCircleIcon className="w-4 h-4 mr-2" />
                                        Cancel
                                    </DropdownMenuItem>
                                ))}
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <ConfirmDialog
                        open={open}
                        onOpenChange={setOpen}
                        title="Are you sure?"
                        description="This action cannot be undone."
                        onConfirm={handleDelete}
                        confirmText="Continue"
                        confirmVariant="destructive"
                        confirmDisabled={deleting}
                        cancelText="Cancel"
                    />
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
