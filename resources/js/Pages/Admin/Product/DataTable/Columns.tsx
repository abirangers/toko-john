import { Product } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { CellAction } from "./CellAction";
import { formatPrice } from "@/lib/utils";
import { Checkbox } from "@/Components/ui/checkbox";
import { DataTableColumnHeader } from "@/Components/DataTable/DataTableColumnHeader";

export const columns: ColumnDef<Product>[] = [
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
        accessorKey: "title",
        header: "Title",
        enableSorting: false,
        enableColumnFilter: true,
    },
    {
        accessorKey: "description",
        header: "Description",
        cell: ({ row }) =>
            row.original.description
                ? row.original.description.substring(0, 10) + "..."
                : "No Description",
        enableSorting: false,
        enableColumnFilter: true,
    },
    {
        accessorKey: "price",
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Price" />
        ),
        cell: ({ row }) =>
            row.original.price ? formatPrice(row.original.price) : "No Price",
        enableSorting: true,
        enableColumnFilter: true,
    },
    {
        accessorKey: "stock",
        header: "Stock",
        cell: ({ row }) => row.original.stock,
        enableSorting: true,
        enableColumnFilter: true,
    },
    {
        accessorKey: "image",
        header: "Image",
        cell: ({ row }) =>
            row.original.image ? (
                <img
                    src={row.original.image}
                    alt={row.original.title}
                    width={50}
                    height={50}
                />
            ) : (
                "No Image"
            ),
        enableSorting: false,
        enableColumnFilter: false,
    },
    {
        accessorKey: "category.name",
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Category" />
        ),
        cell: ({ row }) =>
            row.original.category ? row.original.category.name : "No Category",
        enableSorting: true,
        enableColumnFilter: true,
    },
    {
        id: "actions",
        header: "Actions",
        cell: ({ row }) => <CellAction data={row.original} />,
    },
];
