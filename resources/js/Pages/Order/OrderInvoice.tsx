import React from "react";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Download } from "lucide-react";
import { Button } from "@/Components/ui/button";
import { Order } from "@/types";
import { formatPrice } from "@/lib/utils";

const OrderInvoice: React.FC<{ order: Order }> = ({ order }) => {
    console.log(order);
    return (
        <div className="p-4 px-4 mx-auto max-w-7xl">
            <div className="flex justify-end mb-4">
                <Button variant="default" className="flex items-center justify-center w-10 h-10 p-2 rounded-full">
                    <Download className="w-5" />
                </Button>
            </div>
            <div className="p-6 border rounded-lg text-secondary">
                <h1 className="mb-4 text-2xl font-bold">Detail Payment</h1>
                <p className="mb-2">Order ID: {order.id}</p>
                <p className="mb-2">Order Date: {order.created_at}</p>
                <p className="mb-4">Status: {order.status}</p>
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
                                <TableCell>{item.product.category.name}</TableCell>
                                <TableCell>{item.quantity}</TableCell>
                                <TableCell>{formatPrice(item.product.price)}</TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
                <div className="flex items-center justify-between mt-4">
                    <p className="text-lg font-semibold">Total</p>
                    <p className="text-lg font-semibold">{formatPrice(order.total_price)}</p>
                </div>
            </div>
        </div>
    );
};

export default OrderInvoice;
