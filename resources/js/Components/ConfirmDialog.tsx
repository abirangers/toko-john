import React from "react";
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogTitle,
    DialogDescription,
    DialogFooter,
    DialogClose,
} from "@/Components/ui/dialog";
import { Button } from "@/Components/ui/button";

interface ConfirmDialogProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    title: string;
    description: string;
    onConfirm: () => void;
    confirmText?: string;
    confirmVariant?:
        | "default"
        | "destructive"
        | "outline"
        | "secondary"
        | "ghost"
        | "link";
    confirmDisabled?: boolean;
    cancelText?: string;
}

export const ConfirmDialog: React.FC<ConfirmDialogProps> = ({
    open,
    onOpenChange,
    title,
    description,
    onConfirm,
    confirmText = "Confirm",
    confirmVariant = "primary",
    confirmDisabled = false,
    cancelText = "Cancel",
}) => {
    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogTitle>{title}</DialogTitle>
                <DialogDescription>{description}</DialogDescription>
                <DialogFooter>
                    <DialogClose asChild>
                        <Button
                            variant="ghost"
                            onClick={() => onOpenChange(false)}
                        >
                            {cancelText}
                        </Button>
                    </DialogClose>
                    <DialogClose asChild>
                        <Button
                            variant={confirmVariant as any}
                            onClick={() => {
                                onConfirm();
                                onOpenChange(false);
                            }}
                            disabled={confirmDisabled}
                        >
                            {confirmText}
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};
