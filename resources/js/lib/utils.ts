import { router } from "@inertiajs/react";
import { type ClassValue, clsx } from "clsx";
import { toast } from "sonner";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatPrice(price: number) {
    return Number(price).toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
}

export const getMediaUrl = (filepath: string) => {
    return `${window.location.origin}/storage/${filepath}`;
};

export const bulkDelete = ({
    ids,
    bulkDeleteEndpoint,
    onSuccess,
    onError
}: {
    ids: number[];
    bulkDeleteEndpoint: string;
    onSuccess: () => void;
    onError: () => void;
}) => {
    if (ids.length === 0) {
        toast.error("No rows selected for deletion");
        return;
    }

    router.delete(bulkDeleteEndpoint, {
        data: {ids},
        onSuccess: () => {
            onSuccess();
        },
        onError: () => {
            toast.error("Failed to delete rows");
            onError();
        }
    })
}
