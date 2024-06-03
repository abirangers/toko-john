import * as React from "react";
import { Menu } from "lucide-react";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";

import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";

import { Button } from "../ui/button";
import Logo from "./Logo";
import { Link } from "@inertiajs/react";

const MobileNav = () => {
    return (
        <div className="flex md:hidden">
            <Sheet>
                <SheetTrigger asChild>
                    <Button variant="ghost" size="icon">
                        <Menu />
                    </Button>
                </SheetTrigger>
                <SheetContent side={"left"}>
                    <div>
                        <Logo />
                        <div className="mt-5 text-sm">
                            <Accordion
                                type="multiple"
                                defaultValue={["item-1", "item-2"]}
                            >
                                <AccordionItem value="item-1">
                                    <AccordionTrigger>Lobby</AccordionTrigger>
                                    <AccordionContent>
                                        <div className="flex flex-col text-muted-foreground gap-y-2">
                                            <Link href="/products">
                                                Products
                                            </Link>
                                            <Link href="/#category">
                                                Categories
                                            </Link>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                                <AccordionItem value="item-2">
                                    <AccordionTrigger>
                                        Categories
                                    </AccordionTrigger>
                                    <AccordionContent>
                                        <div className="flex flex-col text-muted-foreground gap-y-2">
                                            <Link href="/products">
                                                Clothing
                                            </Link>
                                            <Link href="/#category">Shoes</Link>

                                            <Link href="/#category">Accessories</Link>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                            </Accordion>
                        </div>
                    </div>
                </SheetContent>
            </Sheet>
        </div>
    );
};

export default MobileNav;
