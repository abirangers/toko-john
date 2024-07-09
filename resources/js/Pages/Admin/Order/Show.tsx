import DashboardLayout from "@/Layouts/DashboardLayout";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Pencil } from "lucide-react";
import { Order } from "@/types";
import { formatDate, formatPrice } from "@/lib/utils";

export default function ShowOrder({ order }: { order: Order }) {
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Order</h1>
                    <p className="text-sm text-muted-foreground">{order.id}</p>
                </div>
                <Button
                    size="sm"
                    onClick={() =>
                        router.get(route("admin.orders.edit", order.id))
                    }
                >
                    <Pencil className="w-3 h-3 mr-2" />
                    Edit
                </Button>
            </div>
            <div className="mt-4 text-secondary">
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Order ID
                    </label>
                    <input
                        type="text"
                        value={order.id}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        User
                    </label>
                    <input
                        type="text"
                        value={order.user.name}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <input
                        type="text"
                        value={order.status}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Total Price
                    </label>
                    <input
                        type="text"
                        value={formatPrice(order.total_price)}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        Tanggal Order
                    </label>
                    <input
                        type="text"
                        value={formatDate(order.created_at)}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <h2 className="text-lg font-semibold">Products</h2>
                    <div className="grid grid-cols-3 gap-4 mt-2">
                        {order.order_items.map((item) => (
                            <div
                                key={item.id}
                                className="p-4 transition-shadow duration-300 border rounded-lg shadow-md hover:shadow-lg"
                            >
                                <img
                                    src={item.product.image}
                                    alt={item.product.title}
                                    className="object-cover w-full h-32 mb-2 rounded-md"
                                />
                                <h2 className="text-lg font-semibold">
                                    {item.product.title} <span className="text-blue-600">x{item.quantity}</span>
                                </h2>
                                <p className="text-sm text-gray-500">
                                    {item.product.category.name}
                                </p>
                                <p className="text-sm text-gray-500">
                                    {formatPrice(item.product.price)}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
