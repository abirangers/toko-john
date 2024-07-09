import { Order } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { CellAction } from "./CellAction";
import { Checkbox } from "@/Components/ui/checkbox";
import { DataTableColumnHeader } from "@/Components/DataTable/DataTableColumnHeader";
import { CheckIcon, XIcon, ClockIcon, Clock } from "lucide-react";
import { formatDate, formatPrice } from "@/lib/utils";

export const columns: ColumnDef<Order>[] = [
    {
        id: "select",
        header: ({ table }) => {
            return (
                <Checkbox
                    checked={
                        table.getIsAllPageRowsSelected() ||
                        (table.getIsSomePageRowsSelected() && "indeterminate")
                    }
                    onCheckedChange={(value) =>
                        table.toggleAllPageRowsSelected(!!value)
                    }
                    aria-label="Select all"
                />
            );
        },
        cell: ({ row }) => {
            return (
                <Checkbox
                    checked={row.getIsSelected()}
                    onCheckedChange={(value) => row.toggleSelected(!!value)}
                    aria-label="Select row"
                />
            );
        },
    },
    {
        accessorKey: "id",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Id" />;
        },
        enableSorting: true,
        enableColumnFilter: true,
    },
    {
        accessorKey: "order_code",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Order Code" />;
        },
    },
    {
        accessorKey: "user.name",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Name" />;
        },
    },
    {
        accessorKey: "user.email",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Email" />;
        },
    },
    {
        accessorKey: "orderItems",
        header: ({ column }) => {
            return (
                <DataTableColumnHeader column={column} title="Total Product" />
            );
        },
        cell: ({ row }) => {
            // length
            console.log(row.original);
            return row.original.order_items.length ?? 0;
        },
    },
    {
        accessorKey: "total",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Total" />;
        },
        cell: ({ row }) => {
            return formatPrice(row.original.total_price);
        },
    },
    {
        accessorKey: "status",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Status" />;
        },
        cell: ({ row }) => {
            const status = row.original.status;
            return (
                <div>
                    {status === "paid" && (
                        <CheckIcon className="text-green-500" />
                    )}
                    {status === "cancelled" && (
                        <XIcon className="text-red-500" />
                    )}
                    {status == "pending" && (
                        <Clock className="text-yellow-500" />
                    )}
                </div>
            );
        },
    },
    {
        accessorKey: "created_at",
        header: ({ column }) => {
            return (
                <DataTableColumnHeader column={column} title="Tanggal Order" />
            );
        },
        cell: ({ row }) => {
            return formatDate(row.original.created_at);
        },
    },
    {
        id: "actions",
        header: "Actions",
        cell: ({ row }) => <CellAction data={row.original} />,
    },
];
