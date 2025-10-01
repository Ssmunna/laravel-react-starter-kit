import Main from "@/Layouts/Backend/Main.jsx";
import {RxUpload} from "react-icons/rx";
import Button from "@/Components/Utils/Button/Button.jsx";
import {useForm, usePage} from "@inertiajs/react";
import {useEffect, useRef, useState} from "react";
import TextEditor from "@/Components/Utils/TextEditor/TextEditor.jsx";
import ImageUploader from "@/Components/Utils/ImageUploader.jsx";
import {generateUniqueNumericId} from "@/helpers/helper.js";

export default function Page({data:content}){
    const {section} = content
    const [forEdit, setForEdit] = useState(false);

    const {data, setData, post, processing, errors, reset} = useForm({
        title: '',
        subtitle: '',
        images: [
            {
                id: generateUniqueNumericId(),
                file: ''
            },
            {
                id: generateUniqueNumericId(),
                file: ''
            },
            {
                id: generateUniqueNumericId(),
                file: ''
            }
        ],
        status: 'active'
    });

    useEffect(() => {
        if(section){
            setData({
                ...data,
                id: section.id ?? '',
                title: section.title ?? '',
                subtitle: section.subtitle ?? '',
                status: section.status ?? '',
                images: JSON.parse(section.images)?.map((img) => {
                    return {
                        id: img.id.toString(),
                        path: img.path
                    }
                }),
            })

            setForEdit(true)
        }else{
            setData({
                ...data,
                title: '',
                subtitle: '',
                status: 'active',
                id: '',
            })

            setForEdit(false)
        }
    }, [section])


    const handleSubmitForm = (e) => {
        e.preventDefault();
        post(route('admin.home.what_we_build.update'), {
            preserveState: true,
            preserveScroll: true,
        })
    }

    return (
        <Main>
            <div className="w-full p-4 rounded bg-gray-100 shadow">
                <ol className="flex items-center whitespace-nowrap">
                    <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                        Dashboard
                        <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                        </svg>
                    </li>
                    <li className="flex items-center text-sm text-gray-800 dark:text-neutral-400">
                        Home
                        <svg className="shrink-0 mx-3 overflow-visible size-2.5 text-gray-400 dark:text-neutral-500" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                        </svg>
                    </li>
                    <li className="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
                        What We Build
                    </li>
                </ol>
            </div>
            <div className="flex items-center justify-between border-b border-gray-300 p-5 border border-gray-300 rounded mt-5">
                <div className="w-full">
                    <form onSubmit={handleSubmitForm} className={`w-full space-y-2`}>
                        <div className="form-control">
                            <label htmlFor="title" className={`label`}>Title <span className={`text-xs text-red-600`}>*</span></label>
                            <TextEditor value={data.title} setValue={(value) => setData(prev => ({...prev, title: value}))} />
                        </div>

                        <div className="form-control">
                            <label htmlFor="subtitle" className={`label`}>Subtitle <span className={`text-xs text-red-600`}>*</span></label>
                            <TextEditor value={data.subtitle} setValue={(value) => setData(prev => ({...prev, subtitle: value}))} />
                        </div>

                        <div className="w-full flex flex-wrap items-center gap-4 mt-5">
                            {
                                data.images.map((image, i) => (
                                    <div key={image.id} className={`w-[130px] h-[150px] relative shrink-0`}>
                                        <ImageUploader
                                            id={image.id}
                                            setData={setData}
                                            images={data.images}
                                            previewImage={forEdit ? image.path : null}
                                            isEdit={forEdit}
                                            isFixed={true}
                                        />
                                    </div>
                                ))
                            }
                        </div>

                        <div className="form-control pt-14 md:pt-4">
                            <div className="w-full flex gap-x-4">
                                <Button buttonText={!section ? 'Create' : 'Update'} isLoading={processing} />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </Main>
    )
}
