import React from "react";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from "@/Components/ui/dialog";
import { Button } from "@/Components/ui/button";

interface ConfirmDialogProps {
    open: boolean;
    onOpenChange: React.Dispatch<React.SetStateAction<boolean>>;
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
    modal?: boolean;
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
    modal = true,
}) => {
    return (
        <Dialog open={open} onOpenChange={onOpenChange} modal={modal}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        variant="outline"
                        onClick={() => onOpenChange(false)}
                    >
                        {cancelText}
                    </Button>
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
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};
