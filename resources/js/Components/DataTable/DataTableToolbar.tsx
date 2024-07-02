import { RowSelectionState, Table } from "@tanstack/react-table";
import { DataTableViewOptions } from "./DataTableViewOptions";
import { DebouncedInput } from "../ui/DebouncedInput";
import { Button } from "../ui/button";

interface DataTableToolbarProps<TData> {
    table: Table<TData>;
    filtering: string;
    setFiltering: (value: string) => void;
    rowSelection: RowSelectionState;
    setOpen: (value: boolean) => void;
}

export function DataTableToolbar<TData>({
    table,
    filtering,
    setFiltering,
    rowSelection,
    setOpen,
}: DataTableToolbarProps<TData>) {
    return (
        <div className="flex items-center justify-between pb-4">
            <div className="flex items-center space-x-2">
                <DataTableViewOptions table={table} />
                <DebouncedInput
                    placeholder="Filter..."
                    value={filtering ?? ""}
                    onChange={(value) => setFiltering(String(value))}
                    className="max-w-sm"
                />
            </div>
            {Object.values(rowSelection).some((isSelected) => isSelected) && (
                <Button
                    onClick={() => setOpen(true)}
                    className="rounded-md"
                    size="sm"
                >
                    Delete Selected
                </Button>
            )}
        </div>
    );
}
