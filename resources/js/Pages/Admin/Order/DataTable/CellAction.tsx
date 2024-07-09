import React from "react";
import { useState } from "react";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
} from "@/Components/ui/dropdown-menu";
import { Trash, Edit, MoreHorizontal, Check, Eye } from "lucide-react";
import { Button } from "@/Components/ui/button";
import { router } from "@inertiajs/react";
import { ConfirmDialog } from "@/Components/ConfirmDialog";
import {  Order } from "@/types";

interface CellActionProps {
    data: Order;
}

export const CellAction: React.FC<CellActionProps> = ({ data }) => {
    const [open, setOpen] = useState(false);
    const [deleting, setDeleting] = useState(false);

    const handleDelete = () => {
        setDeleting(true);
        router.delete(route("admin.orders.destroy", data.id), {
            onSuccess: () => {
                setOpen(false);
                setDeleting(false);
            },
            onError: () => {
                setDeleting(false);
            }
        });
    };

    console.log(data);

    return (
        <>
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button variant="ghost" className="w-8 h-8 p-0 group-hover:bg-red-600">
                        <span className="sr-only">Open menu</span>
                        <MoreHorizontal className="w-4 h-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>Actions</DropdownMenuLabel>
                    {data.status === "pending" && (
                        <DropdownMenuItem onClick={() => router.put(route("admin.orders.confirm", data.id))}>
                            <Check className="w-4 h-4 mr-2" />
                            Confirm
                        </DropdownMenuItem>
                    )}
                    <DropdownMenuItem
                        onClick={() =>
                            router.get(route("admin.orders.show", data.id))
                        }
                        className="cursor-pointer"
                    >
                        <Eye className="w-4 h-4 mr-2" />
                        View Details
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        onClick={() =>
                            router.get(route("admin.orders.edit", data.id))
                        }
                        className="cursor-pointer"
                    >
                        <Edit className="w-4 h-4 mr-2" />
                        Update
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        onClick={() => setOpen(true)}
                        className="cursor-pointer"
                    >
                        <Trash className="w-4 h-4 mr-2" />
                        Delete
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <ConfirmDialog
                open={open}
                onOpenChange={setOpen}
                title="Are you sure?"
                description="This action cannot be undone."
                onConfirm={handleDelete}
                confirmText="Continue"
                confirmVariant="destructive"
                confirmDisabled={deleting}
                cancelText="Cancel"
            />
        </>
    );
};

