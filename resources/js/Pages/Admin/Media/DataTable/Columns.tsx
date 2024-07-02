import { Button } from "@/Components/ui/button";
import { Media } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { CellAction } from "./CellAction";
import { formatPrice, getMediaUrl } from "@/lib/utils";
import { Checkbox } from "@/Components/ui/checkbox";
import { DataTableColumnHeader } from "@/Components/DataTable/DataTableColumnHeader";

export const columns: ColumnDef<Media>[] = [
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
        accessorKey: "user.name",
        header: "User",
        cell: ({ row }) => row.original.user.name,
        enableSorting: false,
        enableColumnFilter: true,
    },
    
    {
        accessorKey: "image",
        header: "Image",
        cell: ({ row }) =>
            row.original.path ? (
                <img
                    src={getMediaUrl(row.original.path)}
                    alt={row.original.name}
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
        id: "actions",
        header: "Actions",
        cell: ({ row }) => <CellAction data={row.original} />,
    },
];
