import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    ColumnDef,
    RowSelectionState,
    SortingState,
    flexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useReactTable,
} from "@tanstack/react-table";
import { useState } from "react";
import { router } from "@inertiajs/react";
import { toast } from "sonner";
import { ConfirmDialog } from "@/Components/ConfirmDialog";
import { DataTablePagination } from "./DataTablePagination";
import { DataTableToolbar } from "./DataTableToolbar";
import { bulkDelete } from "@/lib/utils";

interface Idnetifiable {
    id: number;
}

interface DataTableProps<TData, TValue> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    bulkDeleteEndpoint: string;
}

export function DataTable<TData extends Idnetifiable, TValue>({
    columns,
    data,
    bulkDeleteEndpoint,
}: DataTableProps<TData, TValue>) {
    const [open, setOpen] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const [sorting, setSorting] = useState<SortingState>([]);
    const [filtering, setFiltering] = useState<string>("");
    const [rowSelection, setRowSelection] = useState<RowSelectionState>({});

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        onSortingChange: setSorting,
        getSortedRowModel: getSortedRowModel(),
        onGlobalFilterChange: setFiltering,
        getFilteredRowModel: getFilteredRowModel(),
        onRowSelectionChange: setRowSelection,
        state: {
            sorting,
            globalFilter: filtering,
            rowSelection,
        },
    });

    const handleBulkDelete = () => {
        const selectedIds = Object.keys(rowSelection)
        .filter((key) => rowSelection[key])
        .map((key) => data[parseInt(key)].id);
        
        setDeleting(true);

        bulkDelete({
            ids: selectedIds,
            bulkDeleteEndpoint,
            onSuccess: () => {
                setRowSelection({});
                setOpen(false);
                setDeleting(false);
            },
            onError: () => {
                setDeleting(false);
            },
        });
    };

    return (
        <div>
            <DataTableToolbar
                table={table}
                filtering={filtering}
                setFiltering={setFiltering}
                rowSelection={rowSelection}
                setOpen={setOpen}
            />
            <div className="border rounded-md">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => (
                                    <TableHead key={header.id}>
                                        {header.isPlaceholder
                                            ? null
                                            : flexRender(
                                                  header.column.columnDef
                                                      .header,
                                                  header.getContext()
                                              )}
                                    </TableHead>
                                ))}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => {
                                const isHenriManampiring =
                                    (row.original as { author: string })
                                        .author === "Henri Manampiring";
                                return (
                                    <TableRow
                                        key={row.id}
                                        data-state={
                                            row.getIsSelected() && "selected"
                                        }
                                        className={
                                            isHenriManampiring
                                                ? "bg-red-500 hover:bg-red-600"
                                                : ""
                                        }
                                    >
                                        {row.getVisibleCells().map((cell) => (
                                            <TableCell key={cell.id}>
                                                {flexRender(
                                                    cell.column.columnDef.cell,
                                                    cell.getContext()
                                                )}
                                            </TableCell>
                                        ))}
                                    </TableRow>
                                );
                            })
                        ) : (
                            <TableRow>
                                <TableCell colSpan={columns.length}>
                                    No data
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <DataTablePagination table={table} />
            <ConfirmDialog
                open={open}
                onOpenChange={setOpen}
                title="Are you sure?"
                description="This action cannot be undone."
                onConfirm={() => handleBulkDelete()}
                confirmText="Continue"
                confirmVariant="destructive"
                confirmDisabled={deleting}
                cancelText="Cancel"
            />
        </div>
    );
}
