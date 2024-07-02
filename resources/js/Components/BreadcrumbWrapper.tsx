import React, { Fragment } from "react";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "./ui/breadcrumb";
import { Link } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";

const BreadcrumbWrapper: React.FC = () => {
    const { url } = usePage();
    const pathnames = url.split("/").filter((x) => x);
    pathnames.shift();

    return (
        <Breadcrumb>
            <BreadcrumbList>
                {pathnames.map((name, index) => {
                    const routeTo = `/${pathnames
                        .slice(0, index + 1)
                        .join("/")}`;
                    const linkName =
                        name.charAt(0).toUpperCase() + name.slice(1);
                    const isLast = index === pathnames.length - 1;
                    console.log(routeTo);
                    return (
                        <React.Fragment key={index}>
                            <BreadcrumbItem>
                                {isLast ? (
                                    <BreadcrumbPage>{linkName}</BreadcrumbPage>
                                ) : (
                                    <BreadcrumbLink asChild>
                                        <Link href={`/admin${routeTo}`}>
                                            {linkName}
                                        </Link>
                                    </BreadcrumbLink>
                                )}
                                {pathnames.length !== index + 1 && (
                                    <BreadcrumbSeparator />
                                )}
                            </BreadcrumbItem>
                        </React.Fragment>
                    );
                })}
            </BreadcrumbList>
        </Breadcrumb>
    );
};

export default BreadcrumbWrapper;
