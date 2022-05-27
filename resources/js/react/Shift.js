const DayBlock = function (props) {
    const {date} = props;
    let isRecurring = false;

    return <div className={'border rounded'}>
        <div className={'flex justify-between bg-gray-100 px-5 py-2'}>
            <div>{date.format('dddd')} ({date.format('YYYY-DD-MM')})</div>
            <div className={'flex items-center space-x-2'}>
                <button className={window.classNames('uppercase font-bold text-xs px-2 py-1 rounded', {
                    'text-gray-700 bg-gray-200': !isRecurring,
                    'text-white bg-blue-500': isRecurring
                })}>Recurring</button>
                <button><i className={'fas fa-plus'}></i></button>
            </div>
        </div>
        <div>
            <div className={'grid grid-cols-3 relative gap-4 px-5 py-2'}>
                <div><input type="text" className={'border rounded py-2 px-4 w-full'} placeholder={'Comment (optional)'}/>
                </div>
                <div><input type="time" className={'border rounded py-2 px-4 w-full'}/></div>
                <div className={'flex items-center justify-between space-x-2'}>
                    <input type="time" className={'border rounded py-2 px-4 w-full'}/>
                    <button><i className={'fas fa-trash'}></i></button>
                </div>
            </div>
        </div>
    </div>
}

const WeekBlock = function (props) {
    const {date} = props;
    const blocks = [];

    const endOfWeek = date.clone().endOf('isoWeek');
    const currentDay = date.clone().startOf('isoWeek');

    while (currentDay.isBefore(endOfWeek)) {
        blocks.push(<DayBlock date={currentDay.clone()} key={'date-' + currentDay.format('dddd')}/>);

        currentDay.add(1, 'day');
    }

    return blocks;
}

const Shift = function (props) {
    const date = window.moment(props.date && props.date !== '' ? props.date : undefined);

    const prevWeek = date.clone().add(-1, 'week').startOf('isoWeek').format('YYYY-MM-DD');
    const nextWeek = date.clone().add(1, 'week').startOf('isoWeek').format('YYYY-MM-DD');

    return <div className={'space-y-4'}>
        <div className={'grid grid-cols-2 gap-5'}>
            <div className={'flex justify-end items-center'}>
                <div className={'flex items-center space-x-2'}>
                    <a href={'?date=' + prevWeek} className={'uppercase font-bold py-2 px-4 space-x-2'}><i className={'fas fa-caret-left'}></i>
                        <span>Prev</span>
                    </a>
                    <a href={'?date=' + nextWeek} className={'uppercase font-bold py-2 px-4 space-x-2'}><span>Next</span> <i
                        className={'fas fa-caret-right'}></i></a>
                </div>
            </div>
        </div>

        <div className={'space-y-2'}>
            <WeekBlock date={date}/>
        </div>
    </div>
}

export default Shift;
