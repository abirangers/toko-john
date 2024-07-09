import { cn } from "@/lib/utils";
import { usePage } from "@inertiajs/react";
import axios from "axios";
import { ArrowUp } from "lucide-react";
import React, { useEffect, useRef, useState } from "react";
import { toast } from "sonner";
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogFooter,
    DialogTitle,
    DialogDescription,
    DialogClose,
} from "@/Components/ui/dialog";
import { Button } from "./ui/button";
import { AspectRatio } from "@radix-ui/react-aspect-ratio";

interface MediaLibraryProps {
    multiple?: boolean;
    onConfirm: (selectedMedia: string | string[]) => void;
    selectMultiple?: boolean;
    isInMediaPage?: boolean;
    open: boolean;
    onClose: () => void;
}

interface Media {
    id: number;
    url: string;
}

export default function MediaLibrary({
    multiple = false,
    onConfirm,
    selectMultiple = false,
    isInMediaPage = false,
    open,
    onClose,
}: MediaLibraryProps) {
    const { auth } = usePage().props as unknown as {
        auth: { user: { id: number } };
    };
    const uploadRef = useRef<HTMLInputElement | null>(null);
    const [medias, setMedias] = useState<Media[]>([]);
    const [selectedMedia, setSelectedMedia] = useState<string[]>([]);
    const [uploading, setUploading] = useState(false);
    const [activeTab, setActiveTab] = useState("1"); // 1 Upload, 2 Media Library

    const handleChangeUpload = async (
        e: React.ChangeEvent<HTMLInputElement>
    ) => {
        setUploading(true);
        const files = e.target.files;
        if (!files || files.length === 0) return;

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > 1024 * 1024 * 2) {
                alert("File size exceeds the limit of 2MB");
                setUploading(false);
                return;
            }
        }

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append("files[]", files[i]);
        }
        formData.append("user_id", auth.user.id.toString());

        try {
            const response = await axios.post(
                "/api/media/bulk-store",
                formData,
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );

            const media = response.data.data;
            setMedias((prev) => [...media, ...prev]);
            alert("Media uploaded successfully");

            if (isInMediaPage) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                setActiveTab("2");
                setUploading(false);
            }
        } catch (error) {
            console.log(error);
            alert("Failed to upload media");
            setUploading(false);
        }
    };

    const handleUpload = () => {
        if (uploadRef.current) {
            uploadRef.current.value = "";
            uploadRef.current.click();
        }
    };

    const handleSelectMedia = (url: string) => {
        setSelectedMedia((prev) => {
            if (selectMultiple) {
                return prev.includes(url)
                    ? prev.filter((item) => item !== url)
                    : [...prev, url];
            } else {
                return [url];
            }
        });
    };

    useEffect(() => {
        const fetchMedia = async () => {
            const response = await axios.get("/api/media");
            setMedias(response.data.data);
        };

        fetchMedia();
    }, []);

    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent className="max-w-4xl">
                <div className="flex items-center px-5 border-b border-base-200">
                    <button
                        onClick={() => setActiveTab("1")}
                        className={cn(
                            "px-4 py-2 transition-all",
                            activeTab === "1"
                                ? "border-b-2 font-bold border-primary"
                                : ""
                        )}
                    >
                        Upload New Media
                    </button>
                    <button
                        onClick={() => setActiveTab("2")}
                        className={cn(
                            "px-4 py-2 transition-all",
                            activeTab === "2"
                                ? "border-b-2 font-bold border-primary"
                                : ""
                        )}
                    >
                        Media Library
                    </button>
                </div>
                <div className="px-8 overflow-y-scroll">
                    {activeTab === "1" ? (
                        <div className="h-[calc(100vh-20rem)]">
                            <div className="flex items-center justify-center h-full">
                                <Button
                                    disabled={uploading}
                                    onClick={handleUpload}
                                >
                                    <ArrowUp />
                                    {uploading
                                        ? "Uploading..."
                                        : "Upload Media"}
                                </Button>
                                <input
                                    multiple={multiple}
                                    ref={uploadRef}
                                    className="fixed top-[-100%] left-[-100%] opacity-0"
                                    type="file"
                                    onChange={handleChangeUpload}
                                />
                            </div>
                        </div>
                    ) : activeTab === "2" ? (
                        <div className="overflow-y-scroll h-[calc(100vh-20rem)]">
                            <h1 className="pb-4 mb-4 font-bold">All Media</h1>
                            <div className="grid grid-cols-2 gap-4 lg:grid-cols-3">
                                {medias.map((media, index) => (
                                    <AspectRatio
                                        ratio={16 / 9}
                                        onClick={() =>
                                            handleSelectMedia(media.url)
                                        }
                                        key={`media-${index}`}
                                        className={cn(
                                            "border border-base-300 overflow-hidden",
                                            selectedMedia.includes(media.url)
                                                ? "border-2 border-secondary"
                                                : ""
                                        )}
                                    >
                                        <img
                                            src={media.url}
                                            alt={media.url}
                                            className="object-cover w-full h-full"
                                        />
                                    </AspectRatio>
                                ))}
                            </div>
                        </div>
                    ) : (
                        <></>
                    )}
                </div>
                <div className="pr-6">
                    <form method="dialog" className="flex items-center gap-2">
                        {activeTab === "2" && (
                            <Button
                                variant="default"
                                onClick={() => {
                                    onConfirm(
                                        selectMultiple
                                            ? selectedMedia
                                            : selectedMedia[0]
                                    );
                                    setSelectedMedia([]);
                                }}
                            >
                                Select Media
                            </Button>
                        )}

                        <DialogClose asChild>
                            <Button variant="outline" className="rounded-lg">
                                Close
                            </Button>
                        </DialogClose>
                    </form>
                </div>
            </DialogContent>
        </Dialog>
    );
}
