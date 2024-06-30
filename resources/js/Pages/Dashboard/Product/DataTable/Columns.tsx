import { Button } from "@/Components/ui/button";
import { Category, Product } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { CellAction } from "./CellAction";
import { formatPrice } from "@/lib/utils";

export const columns: ColumnDef<Product>[] = [
    {
        accessorKey: "no",
        header: "No",
        cell: ({ row }) => row.index + 1,
    },
    {
        accessorKey: "slug",
        header: "Slug",
    },
    {
        accessorKey: "title",
        header: "Title",
    },
    {
        accessorKey: "description",
        header: "Description",
        cell: ({ row }) =>
            row.original.description
                ? row.original.description.substring(0, 10) + "..."
                : "No Description",
    },
    {
        accessorKey: "price",
        header: "Price",
        cell: ({ row }) =>
            row.original.price ? formatPrice(row.original.price) : "No Price",
    },
    {
        accessorKey: "image",
        header: "Image",
        cell: ({ row }) =>
            row.original.image ? (
                <img
                    src={`/images/products/${row.original.image}`}
                    alt={row.original.title}
                    width={50}
                    height={50}
                />
            ) : (
                "No Image"
            ),
    },
    {
        accessorKey: "category.name",
        header: "Category",
    },
    {
        id: "actions",
        header: "Actions",
        cell: ({ row }) => <CellAction data={row.original} />,
    },
];
