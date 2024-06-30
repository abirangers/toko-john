import * as React from 'react';
import {PropsWithChildren} from 'react';

export default function Guest({children}: PropsWithChildren) {
    return (
        <div className="flex flex-col items-center min-h-screen p-4 sm:justify-center sm:pt-0">
            {children}
        </div>
    );
}   
