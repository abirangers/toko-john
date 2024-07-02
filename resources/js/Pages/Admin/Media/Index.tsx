import DashboardLayout from "@/Layouts/DashboardLayout";
import { Media } from "@/types";
import React, { useMemo, useState } from "react";
import { Button } from "@/Components/ui/button";
import { Plus } from "lucide-react";
import { DataTable } from "../../../Components/DataTable/DataTable";
import { columns as columnDefs } from "./DataTable/Columns";
import MediaLibrary from "@/Components/MediaLibrary";

const IndexMedia = ({ medias }: { medias: Media[] }) => {
    const [open, setOpen] = useState(false);
    const columns = useMemo(() => columnDefs, []);
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">
                        Media ({medias.length})
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Media is a list of media that are available in the
                        store.
                    </p>
                </div>
                <Button
                    className="rounded-md"
                    size="sm"
                    onClick={(e) => {
                        e.preventDefault();
                        setOpen(true);
                    }}
                >
                    <Plus className="w-5 h-5 mr-2" />
                    Add New
                </Button>
            </div>
            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={medias}
                    bulkDeleteEndpoint={route("admin.media.bulkDestroy")}
                />
            </div>

            <MediaLibrary
                open={open}
                onClose={() => setOpen(false)}
                onConfirm={() => {}}
                multiple={true}
                isInMediaPage={true}
            />
        </DashboardLayout>
    );
};

export default IndexMedia;
