import React from "react";
import Main from "@/Layouts/Frontend/Main.jsx";
import sectionShape4 from "../../../../../public/assets/images/section/sectionShape4.png";
import {usePage} from "@inertiajs/react";

const Page = ({ data }) => {
    const {fileBase} = usePage().props
    const {hero_section} = data;
    return (
        <Main>
            <div
                className={`bg-[#111111] flex items-center text-white h-[450px] md:h-[826px] w-full bg-cover`}
                style={{ backgroundImage: `url(${fileBase}/${hero_section.image})` }}
            >
                <div className="w-full px-5">
                    <div className="max-w-[1250px] m-auto text-[22px] md:text-[46px] flex justify-center items-start">
                        <h3 className="font-[700] max-w-[750px] text-center leading-[32px] md:leading-[50px]" dangerouslySetInnerHTML={{ __html: hero_section.title }}></h3>
                    </div>
                </div>
            </div>
        </Main>
    );
};

export default Page;
