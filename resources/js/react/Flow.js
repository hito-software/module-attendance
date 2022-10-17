import React from "react";
import classNames from "classnames";
import Choices from "choices.js";

const TypeEnum = {
    PEOPLE: 'People',
    DEPARTMENT: 'Department',
    TEAM_ROLE: 'Team role',
    PROJECT_ROLE: 'Project role',
}

const ChoiceSelector = ({placeholder, value, onChange, config, options, multiple}) => {
    const [choiceInstance, setChoiceInstance] = React.useState(null);
    const elementRef = React.useRef(null);

    React.useEffect(() => {
        if (choiceInstance) {
            return;
        }

        if (elementRef?.current) {
            setChoiceInstance(new Choices(elementRef.current, config ?? {}));
        }
    }, [elementRef, choiceInstance]);

    return <select className={'p-2 border rounded-lg w-full block'}
                   ref={elementRef}
                   multiple={multiple}
                   value={value} onChange={(e) => onChange(e.target.value)}>
        {placeholder && <option value="">{placeholder}</option>}
        {options?.length ? options.map((option) => <option key={option.value} value={option.value}>{option.label}</option>) : false}
    </select>;
};

const ModalGroup = (props) => {
    const {onTypeChange, onSelectionChange, onDelete, canDelete} = props;
    const [type, updateType] = React.useState(props.type ?? '');
    const [selection, updateSelection] = React.useState(props.selection ?? []);
    const [options, updateOptions] = React.useState([]);

    React.useEffect(() => onTypeChange(type), [type, onTypeChange]);
    React.useEffect(() => onSelectionChange(selection), [selection, onSelectionChange]);

    React.useEffect(() => {
        updateSelection([]);

        switch (type) {
            case TypeEnum.PEOPLE:
                updateOptions([
                    {
                        value: '1',
                        label: 'John Doe'
                    },
                    {
                        value: '2',
                        label: 'Jane Doe'
                    },
                    {
                        value: '3',
                        label: 'Tom Doe'
                    },
                    {
                        value: '4',
                        label: 'Craig Doe'
                    }
                ]);
                break;

            case TypeEnum.DEPARTMENT:
                updateOptions([
                    {
                        value: '1',
                        label: 'Administrators'
                    },
                    {
                        value: '2',
                        label: 'DFA'
                    }
                ]);
                break;

            case TypeEnum.PROJECT_ROLE:
                updateOptions([
                    {
                        value: '1',
                        label: 'Project Leader'
                    },
                    {
                        value: '2',
                        label: 'Project Manager'
                    }
                ]);
                break;

            case TypeEnum.TEAM_ROLE:
                updateOptions([
                    {
                        value: '1',
                        label: 'Team Leader'
                    },
                    {
                        value: '2',
                        label: 'Technical Leader'
                    }
                ]);
                break;

            default:
                updateOptions([]);
                break;
        }
    }, [type]);

    return <div className={'border-l-8 border-l-green-600 p-5 rounded-lg shadow border space-y-2 relative'}>
        {canDelete && <button type={'button'}
            onClick={() => onDelete()}
            className="absolute top-0 right-0 w-6 h-6 flex items-center justify-center bg-red-600 rounded-tr-lg rounded-bl-lg text-white">
            <i className={'fa-solid fa-times'}></i>
        </button>}
        <div className={'flex flex-col flex-wrap gap-2'}>
            <div className={'space-y-2'}>
                <ChoiceSelector placeholder={'Select type'}
                                value={type}
                                onChange={updateType}
                                options={Object.values(TypeEnum).map(value => ({
                                    label: value,
                                    value
                                }))} />
            </div>
            {type ? <div className={'space-y-2'}>
                {options.length ? <ChoiceSelector placeholder={'Select'}
                                                  value={selection}
                                                  onChange={updateSelection}
                                                  multiple={true}
                                                  options={options} /> : <div>There are no options to select from.</div>}
            </div> : false}
        </div>
    </div>
}
ModalGroup.defaultProps = {
    onTypeChange: () => {},
    onSelectionChange: () => {},
    onDelete: () => {},
    canDelete: false
}

const SelectionModal = ({show, onSave, onClose, value}) => {
    const [groups, updateGroups] = React.useState([
        {
            id: `new-${(Math.random() + 1).toString(36).substring(2)}`,
        }
    ]);
    const save = React.useCallback((type) => {
        onSave(type);
        onClose();
    }, []);

    const deleteGroup = React.useCallback((group) => {
        updateGroups([...groups].filter(item => item.id !== group.id));
    }, [groups]);

    if (!show) {
        return false;
    }

    return (<div className={'hito-attendance__Flow__SelectionModal'}>
        <div className={'hito-attendance__Flow__SelectionModal__Content'}>
            <div className={'flex-1'}>
                <div className={'space-y-4'}>
                    <div className="space-y-4">
                        {groups.map((group, index) => <>
                            {index > 0 ? <div key={`or-${group.id}`} className="hito-attendance__Flow__SelectionModal__OrWrapper">
                                <div className={'hito-attendance__Flow__SelectionModal__Or'}>OR</div>
                            </div> : false}
                            <ModalGroup key={group.id}
                                        canDelete={groups.length > 1}
                                        onDelete={() => deleteGroup(group)} />
                        </>)}
                    </div>
                    <button type={'button'}
                            onClick={() => updateGroups([
                                ...groups,
                                {
                                    id: `new-${(Math.random() + 1).toString(36).substring(2)}`,
                                }
                            ])}
                            className={'hito-attendance__Flow__SelectionModal__AddBtn'}>
                <span
                    className={'hito-attendance__Flow__SelectionModal__AddBtnIcon'}>
                    <i className={'fa-solid fa-plus'}></i>
                </span>
                        <span className={'hito-attendance__Flow__SelectionModal__AddBtnLabel'}>Add group</span>
                    </button>
                </div>
            </div>
            <div className={'flex flex-col md:flex-row items-center gap-2'}>
                <button type={'button'}
                        onClick={() => save('[Team Role: Team Leader] OR [Project Role: Project Leader] OR [Department: DFA] OR [People: John Doe, Jane Doe]')}
                        className={'hito-attendance__Flow__SelectionModal__Btn hito-attendance__Flow__SelectionModal__Btn--save'}>Save</button>
                <button type={'button'}
                        onClick={() => onClose()}
                        className={'hito-attendance__Flow__SelectionModal__Btn hito-attendance__Flow__SelectionModal__Btn--cancel'}>Cancel</button>
            </div>
        </div>
    </div>);
};

const Value = (props) => {
    const {value, onUpdate} = props;
    const [showModal, setShowModal] = React.useState(false);

    return (<>
        <SelectionModal show={showModal}
                        value={value}
                        onSave={onUpdate}
                        onClose={React.useCallback(() => setShowModal(false), [])} />

        <div className="hito-attendance__Flow__Value">
            <div>
                <button type={'button'} onClick={React.useCallback(() => setShowModal(true), [])}
                        className={'hito-attendance__Flow__Value__Btn'}>
                    {value ? <span>{value}</span> : <span className={'hito-attendance__Flow__Value__Placeholder'}>Select value</span>}
                    <span><i className={'fa-solid fa-chevron-right'}></i></span>
                </button>
            </div>
            <div>
                <button type={'button'} onClick={React.useCallback(() => onUpdate(''), [])} className={classNames('hito-attendance__Flow__Value__ClearBtn', {
                    'hito-attendance__Flow__Value__ClearBtn--hidden': !value
                })}>
                    <span>Clear</span>
                    <i className={'fa-solid fa-times'}></i>
                </button>
            </div>
        </div>
    </>);
}

const ValueGroup = ({label, value, onUpdate}) => {
    return <div className={'hito-attendance__Flow__ValueGroup'}>
        {label && <div>
            <div className={'hito-attendance__Flow__ValueGroup__Label'}>{label}</div>
        </div>}
        <div>
            <Value value={value} onUpdate={onUpdate}/>
        </div>
    </div>
}

const Values = ({child}) => {
    const [main, updateMain] = React.useState('');
    const [alternative, updateAlternative] = React.useState('');

    return (<>
        <ValueGroup label={child ? 'Or' : ''} value={main} onUpdate={updateMain}/>
        {main && <ValueGroup label={'Or'} value={alternative} onUpdate={updateAlternative}/>}

        {alternative && <Values child={true}/>}
    </>)
}

const Group = () => {
    return (<>
        <div className={'hito-attendance__Flow__Group'}>
            <div className={'hito-attendance__Flow__Group__Wrapper'}>
                <Values/>
            </div>
        </div>
    </>)
};

export default function Flow(props) {
    const [groups, updateGroups] = React.useState([
        {
            id: `new-${(Math.random() + 1).toString(36).substring(2)}`,
        }
    ]);

    return (<div className={'hito-attendance__Flow'}>
        <div className={'hito-attendance__Flow__MainLabel'}>Definition</div>
        <div className={'hito-attendance__Flow__ValidationTypeGroup'}>
            <div className={'hito-attendance__Flow__ValidationTypeWrapper'}>
                <p>This flow is valid when</p>
                <div>
                    <select
                        className={'hito-attendance__Flow__ValidationTypeSelect'}>
                        <option value="any">Any</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <p>groups are true:</p>
            </div>
        </div>
        <div className={'space-y-4'}>
            {groups.map(group => <Group key={group.id} group={group}/>)}
        </div>
        <div>
            <button type={'button'}
                    onClick={() => updateGroups([
                        ...groups,
                        {
                            id: `new-${(Math.random() + 1).toString(36).substring(2)}`,
                        }
                    ])}
                    className={'hito-attendance__Flow__AddGroupBtn'}>
                <span
                    className={'hito-attendance__Flow__AddGroupIcon'}>
                    <i className={'fa-solid fa-plus'}></i>
                </span>
                <span className={'hito-attendance__Flow__AddGroupLabel'}>Add group</span>
            </button>
        </div>
    </div>);
}
