import * as React from "react";
import { useMediaQuery } from "@/hooks/useMediaQuery";
import { Button } from "@/Components/ui/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/Components/ui/command";
import { Drawer, DrawerContent, DrawerTrigger } from "@/Components/ui/drawer";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";

type Item = {
    value: string;
    label: string;
};

interface ComboBoxResponsiveProps {
    items: Item[];
    placeholder: string;
    value: string;
    onChange: (value: string) => void;
}

export function ComboBoxResponsive({
    items,
    placeholder,
    value,
    onChange,
}: ComboBoxResponsiveProps) {
    const [open, setOpen] = React.useState(false);
    const isDesktop = useMediaQuery("(min-width: 768px)");
    const selectedItem = items.find((item) => item.value === value) || null;

    const handleSelect = (value: string) => {
        console.log(value);
        onChange(value);
        setOpen(false);
    };

    if (isDesktop) {
        return (
            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild className="mt-2">
                    <Button variant="outline" className="justify-start w-full">
                        {selectedItem ? (
                            <>{selectedItem.label}</>
                        ) : (
                            <>{placeholder}</>
                        )}
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-full p-0" align="start">
                    <ItemList
                        items={items}
                        setOpen={setOpen}
                        onChange={handleSelect}
                    />
                </PopoverContent>
            </Popover>
        );
    }

    return (
        <Drawer open={open} onOpenChange={setOpen}>
            <DrawerTrigger asChild>
                <Button variant="outline" className="w-[150px] justify-start">
                    {selectedItem ? (
                        <>{selectedItem.label}</>
                    ) : (
                        <>{placeholder}</>
                    )}
                </Button>
            </DrawerTrigger>
            <DrawerContent>
                <div className="mt-4 border-t">
                    <ItemList
                        items={items}
                        setOpen={setOpen}
                        onChange={handleSelect}
                    />
                </div>
            </DrawerContent>
        </Drawer>
    );
}

function ItemList({
    items,
    setOpen,
    onChange,
}: {
    items: Item[];
    setOpen: (open: boolean) => void;
    onChange: (value: string) => void;
}) {
    return (
        <Command className="w-full">
            <CommandInput placeholder="Filter items..." />
            <CommandList>
                <CommandEmpty>No results found.</CommandEmpty>
                <CommandGroup>
                    {items.map((item) => (
                        <CommandItem
                            key={item.value}
                            value={item.value}
                            onSelect={() => {
                                onChange(item.value);
                                setOpen(false);
                            }}
                        >
                            {item.label}
                        </CommandItem>
                    ))}
                </CommandGroup>
            </CommandList>
        </Command>
    );
}
