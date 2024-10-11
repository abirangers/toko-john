import React from "react";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Download } from "lucide-react";
import { Button } from "@/Components/ui/button";
import { Order } from "@/types";
import { formatDate, formatPrice } from "@/lib/utils";

const OrderInvoice: React.FC<{ order: Order }> = ({ order }) => {
    return (
        <div className="p-4 px-4 mx-auto max-w-7xl">
            <div className="flex justify-end mb-4">
                <Button
                    variant="default"
                    className="flex items-center justify-center w-10 h-10 p-2 rounded-full"
                >
                    <Download className="w-5" />
                </Button>
            </div>
            <div className="p-6 border rounded-lg text-secondary">
                <h1 className="mb-4 text-2xl font-bold">Detail Payment</h1>
                <p className="mb-2">Order ID: {order.id}</p>
                <p className="mb-2">Order Code: {order.order_code}</p>
                <p className="mb-2">
                    Order Date: {formatDate(order.created_at)}
                </p>
                <p className="mb-2">Status: {order.status}</p>
                <p className="mb-2">Name: {order.user.name}</p>
                <p className="mb-2">Email: {order.user.email}</p>
                <p className="mb-4">
                    Address:{" "}
                    <span className="uppercase">
                        {order.address}, {order.village_name}, Kecamatan{" "}
                        {order.district_name}, {order.regency_name},{" "}
                        {order.province_name}
                    </span>
                </p>
                <h2 className="mb-4 text-xl font-bold">Order Items</h2>
                <Table className="border">
                    <TableHeader>
                        <TableRow>
                            <TableHead>Product Name</TableHead>
                            <TableHead>Product Category</TableHead>
                            <TableHead>Quantity</TableHead>
                            <TableHead>Price</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {order.order_items.map((item) => (
                            <TableRow>
                                <TableCell>{item.product.title}</TableCell>
                                <TableCell>
                                    {item.product.category.name}
                                </TableCell>
                                <TableCell>{item.quantity}</TableCell>
                                <TableCell>
                                    {formatPrice(item.product.price)}
                                </TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                    <TableFooter>
                        <TableRow>
                            <TableCell colSpan={3}>Total</TableCell>
                            <TableCell>{formatPrice(order.total_price)}</TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
            </div>
        </div>
    );
};

export default OrderInvoice;
