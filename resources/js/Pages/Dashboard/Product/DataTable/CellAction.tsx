import React from "react";
import { Book } from "./Columns";
import { useState } from "react";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
} from "@/Components/ui/dropdown-menu";
import { Trash, Edit, MoreHorizontal, Check } from "lucide-react";
import { Button } from "@/Components/ui/button";
import { router } from "@inertiajs/react";

interface CellActionProps {
    data: Book;
}

export const CellAction: React.FC<CellActionProps> = ({ data }) => {
    const [open, setOpen] = useState(false);
    const [deleting, setDeleting] = useState(false);

    const handleDelete = () => {
        setDeleting(true);
        router.delete(route("admin.products.destroy", data.id), {
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
                        onClick={() => router.get(route("admin.products.edit", data.id))}
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

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Are you sure?</DialogTitle>
                        <DialogDescription>
                            This action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="ghost" onClick={() => setOpen(false)}>
                            Cancel
                        </Button>
                        <Button variant="destructive" onClick={handleDelete} disabled={deleting}>
                            Continue
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
};
