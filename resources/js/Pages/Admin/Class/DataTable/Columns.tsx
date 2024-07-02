import { Class, Major } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import { CellAction } from "./CellAction";
import { formatPrice } from "@/lib/utils";
import { Checkbox } from "@/Components/ui/checkbox";
import { DataTableColumnHeader } from "@/Components/DataTable/DataTableColumnHeader";

export const columns: ColumnDef<Class>[] = [
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
        accessorKey: "name",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Name" />;
        },
    },
    {
        accessorKey: "major.name",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Major" />;
        },
    },
    {
        accessorKey: "slug",
        header: ({ column }) => {
            return <DataTableColumnHeader column={column} title="Slug" />;
        },
    },
    {
        id: "actions",
        header: "Actions",
        cell: ({ row }) => <CellAction data={row.original} />,
    },
];
