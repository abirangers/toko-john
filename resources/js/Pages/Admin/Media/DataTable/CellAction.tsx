import React from "react";
import { Book } from "./Columns";
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
import { Media } from "@/types";

interface CellActionProps {
    data: Media;
}

export const CellAction: React.FC<CellActionProps> = ({ data }) => {
    const [open, setOpen] = useState(false);
    const [deleting, setDeleting] = useState(false);

    const handleDelete = () => {
        setDeleting(true);
        router.delete(route("admin.media.destroy", data.id), {
            onSuccess: () => {
                setOpen(false);
                setDeleting(false);
            },
            onError: () => {
                setDeleting(false);
            }
        });
    };

    return (
        <>
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button variant="ghost" className="w-8 h-8 p-0">
                        <span className="sr-only">Open menu</span>
                        <MoreHorizontal className="w-4 h-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>Actions</DropdownMenuLabel>
                    {data.author === "Henri Manampiring" && (
                        <DropdownMenuItem>
                            <Check className="w-4 h-4 mr-2" />
                            Confirm
                        </DropdownMenuItem>
                    )}
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

