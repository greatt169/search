class Catalog extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isWithSpinner: true,
            isHasError: false,
            errorMsg: "Something went wrong!",
            emptyMsg: "No results!",
            result: null,
            rangeParams: [],
            selectParams: [],
            filterParams: props.filter
        };

        this.addSpinner = this.addSpinner.bind(this);
        this.removeSpinner = this.removeSpinner.bind(this);
        this.filterSelectCheckboxHandle = this.filterSelectCheckboxHandle.bind(this);
        this.reload = this.reload.bind(this);
        this.addSelectParamToFilter = this.addSelectParamToFilter.bind(this);
        this.removeSelectParamFromFilter = this.removeSelectParamFromFilter.bind(this);
        this.filterRangeHandle = this.filterRangeHandle.bind(this);
        this.resetFilter = this.resetFilter.bind(this);
    }
    componentDidMount() {
        this.reload();
    }
    reload() {
        fetch(this.props.api, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: this.state.filterParams
        })
            .then(res => res.json())
            .then(
                (result) => {
                    const rangeParams = [];
                    const selectParams = [];
                    if (result !== null) {
                        for (let [code, param] of Object.entries(result.filter.rangeParams)) {
                            rangeParams.push(param);
                        }
                        for (let [code, param] of Object.entries(result.filter.selectParams)) {
                            selectParams.push(param);
                        }
                    }
                    this.setState({
                        result: result,
                        rangeParams: rangeParams,
                        selectParams: selectParams
                    });
                },
                (error) => {

                    this.setState({
                        isHasError: true,
                    });

                    const debug = this.props.debug;
                    if (debug) {
                        this.setState({
                            errorMsg: error,
                        });
                    }

                },
                this.removeSpinner()
            )
    }
    addSpinner() {
        this.setState({
            isWithSpinner: true
        });
    }
    removeSpinner() {
        this.setState({
            isWithSpinner: false
        });
    }
    addSelectParamToFilter(propCode, valueCode) {
        let currFilterParams = JSON.parse(this.state.filterParams);
        let index = currFilterParams['filter']['selectParams'].findIndex(el => el.code === propCode);

        if (index === -1) {
            currFilterParams['filter']['selectParams'].push({
                "code": propCode,
                "values": [
                    {
                        "value": valueCode
                    }
                ]
            });
        } else {
            currFilterParams['filter']['selectParams'][index]["values"].push({
                "value": valueCode
            });
        }
        this.state.filterParams = JSON.stringify(currFilterParams);
    }
    removeSelectParamFromFilter(propCode, valueCode) {
        let currFilterParams = JSON.parse(this.state.filterParams);
        let index = currFilterParams['filter']['selectParams'].findIndex(el => el.code === propCode);
        if (index !== -1) {
            currFilterParams['filter']['selectParams'][index]['values'] = currFilterParams['filter']['selectParams'][index]['values'].filter(function (el) {
                return el.value !== valueCode;
            });
            this.state.filterParams = JSON.stringify(currFilterParams);
        }
    }
    filterRangeHandle(prop, type, value) {
        let filterRangeParams = this.state.rangeParams;
        let val = Number(value.replace(' ', ''));
        let currFilterParams = JSON.parse(this.state.filterParams);
        let index = currFilterParams['filter']['rangeParams'].findIndex(el => el.code === prop);
        let otherType = type === 'maxValue' ? 'minValue' : 'maxValue';
        let otherTypeShort = type === 'maxValue' ? 'min' : 'max';
        let rangeIndex = filterRangeParams.findIndex(el => el.code === prop);
        let otherTypeVal = Number(filterRangeParams[rangeIndex][otherTypeShort]['selected'].toString().replace(' ', ''));

        if (index !== -1) {
            currFilterParams['filter']['rangeParams'][index][type] = val;
            currFilterParams['filter']['rangeParams'][index][otherType] = otherTypeVal;
            this.state.filterParams = JSON.stringify(currFilterParams);
        } else {
            let rangeVal = {"code": prop};
            rangeVal[type] = val;
            rangeVal[otherType] = otherTypeVal;
            currFilterParams['filter']['rangeParams'].push(rangeVal);
        }

        this.state.filterParams = JSON.stringify(currFilterParams);
        this.reload();
    }
    filterSelectCheckboxHandle(e) {

        const name = e.target.name;
        const splitName = name.split('_');
        const propCode = splitName[0];
        const valueCode = splitName[1];

        if (e.target.checked) {
            this.addSelectParamToFilter(propCode, valueCode);
        } else {
            this.removeSelectParamFromFilter(propCode, valueCode);
        }
        this.reload();

    }

    resetFilter()
    {
        let currFilterParams = JSON.parse(this.state.filterParams);
        currFilterParams['filter'] = {"selectParams": [], "rangeParams": []};
        this.state.filterParams = JSON.stringify(currFilterParams);
        this.reload();
    }

    render() {

        const isWithSpinner = this.state.isWithSpinner;
        const isHasError = this.state.isHasError;
        const errorMsg = this.state.errorMsg;
        const result = this.state.result;
        const references = JSON.parse(this.props.references);
        const rangeParams = this.state.rangeParams;
        const selectParams = this.state.selectParams;
        let spinnerClassName;

        spinnerClassName = isWithSpinner ? "row center spinner-border" : "";

        return (
            <div className={spinnerClassName}>
                {isHasError &&
                <div className='alert alert-danger'>{errorMsg.toString()}</div>
                }
                {result !== null &&
                    <div>
                        <div className="card">
                            <div className="card-body">
                                <div className="row">
                                    <div className="col-md-3-24"><strong>Сортировать по:</strong></div>
                                    <nav className="col-md-8-24">
                                        <select className="custom-select" name="" id="">
                                            <option value="1">Популярности</option>
                                            <option value="2">Цене</option>
                                        </select>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div className="row">
                            <aside className="col-sm-3">

                                <div className="card card-filter">
                                    <article className="card-group-item">

                                        <div className="card-body">
                                            <h4>Фильтр</h4>
                                            <div>
                                        <span>
                                            Найдено: ({result.total})
                                        </span>
                                            </div>
                                        </div>
                                    </article>
                                    <FilterRangeParams rangeParams={rangeParams} references={references}
                                                       filterRangeHandle={this.filterRangeHandle}/>
                                    <FilterSelectParams selectParams={selectParams} references={references}
                                                        filterSelectCheckboxHandle={this.filterSelectCheckboxHandle}/>

                                    <article className="card-group-item">
                                        <div className="card-body">
                                            <a href="#" onClick={this.resetFilter}>Сбросить фильтр</a>
                                        </div>
                                    </article>

                                </div>
                            </aside>
                            <ItemsList result={result}/>
                        </div>
                    </div>

                }
            </div>
        )
    }
}

class ItemsList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            emptyMsg: "No results!",
        };
    }
    render() {
        const emptyMsg = this.state.emptyMsg;

        if (this.props.result.total === 0) {
            return (
                <main className="col-sm-9">
                    <div className='alert alert-primary'>{emptyMsg.toString()}</div>
                </main>
            );
        }
        return (
            <main className="col-sm-9">
                {this.props.result["items"].map((item) =>
                    <article key={item.id} className="card card-product">
                        <div className="card-body">
                            <div className="row">
                                <aside className="col-sm-3">
                                    <div className="img-wrap">
                                        <img alt={`${item.singleAttributes.name.value.value}`}
                                             src={`${item.singleAttributes.picture.value.value}`}/>
                                    </div>
                                </aside>
                                <article className="col-sm-6">
                                    <h4 className="title">{item.singleAttributes.name.value.value}</h4>
                                    <p> {item.singleAttributes.preview.value.value}</p>
                                    <dl className="dlist-align">
                                        <dt>Цвет</dt>
                                        {item.multipleAttributes.color["values"].map((attrValue) =>
                                            <dd key={attrValue.code}>{attrValue.value}</dd>
                                        )}
                                    </dl>
                                    <dl className="dlist-align">
                                        <dt>Марка</dt>
                                        <dd>{item.singleAttributes.brand.value.value}</dd>
                                    </dl>
                                    <dl className="dlist-align">
                                        <dt>Модель</dt>
                                        <dd>{item.singleAttributes.model.value.value}</dd>
                                    </dl>
                                    <dl className="dlist-align">
                                        <dt>Год вып.</dt>
                                        <dd>{item.singleAttributes.year.value.value}</dd>
                                    </dl>
                                    <dl className="dlist-align">
                                        <dt>Страховка</dt>
                                        {item.multipleAttributes.insurance["values"].map((attrValue) =>
                                            <dd key={attrValue.code}>{attrValue.value}</dd>
                                        )}
                                    </dl>
                                </article>
                                <aside className="col-sm-3 border-left">
                                    <div className="action-wrap">
                                        <div className="price-wrap h4">
                                                        <span
                                                            className="price"> {item.singleAttributes.price.value.value} руб. </span>
                                        </div>
                                        <p className="text-success">Нет участвовал в ДТП</p>
                                        <p>
                                            <a href="#"><i className="fa fa-heart"></i>Добавить в
                                                избранное</a>
                                        </p>
                                        <p>
                                            <a href="#" className="btn btn-secondary"> Подробнее </a>
                                            &nbsp;
                                            <a href="#" className="btn btn-primary"> Купить </a>
                                        </p>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </article>
                )}

                <nav aria-label="...">
                    <ul className="pagination">
                        <li className="page-item disabled">
                            <a className="page-link" href="#" tabIndex="-1">Previous</a>
                        </li>
                        <li className="page-item"><a className="page-link" href="#">1</a></li>
                        <li className="page-item active">
                            <a className="page-link" href="#">2 <span className="sr-only">(current)</span></a>
                        </li>
                        <li className="page-item"><a className="page-link" href="#">3</a></li>
                        <li className="page-item">
                            <a className="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>

            </main>

        );
    }
}

class FilterRangeParams extends React.Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        let slider = document.getElementById('slider');
        let from = document.getElementById('slider-limit-value-from');
        let to = document.getElementById('slider-limit-value-to');
        let component = this;
        let prop = slider.dataset.prop;

        noUiSlider.create(slider, {
            start: [from.innerHTML, to.innerHTML],
            connect: true,
            step: 1,
            range: {
                'min': Number(from.dataset.total),
                'max': Number(to.dataset.total),
            },
            format: wNumb({
                decimals: 0,
                thousand: ' '
            })
        });

        slider.noUiSlider.on('update', function (values, handle) {
            (handle ? to : from).innerHTML = values[handle];
        });

        slider.noUiSlider.on('end', function (values, handle) {
            let type = handle ? "maxValue" : "minValue";
            let value = values[handle];
            component.props.filterRangeHandle(prop, type, value);
        });
    }
    componentDidUpdate() {
        let slider = document.getElementById('slider');

        let from = document.getElementById('slider-limit-value-from');
        let to = document.getElementById('slider-limit-value-to');

        slider.noUiSlider.updateOptions({
            start: [from.innerHTML, to.innerHTML],
            range: {
                'min': Number(from.dataset.total),
                'max': Number(to.dataset.total),
            }
        });
    }
    render() {
        return (
            this.props.rangeParams.map((param) =>
                <article key={param.code} className="card-group-item">
                    <header className="card-header">
                        <h6 className="title">{this.props.references.properties[param.code].title} </h6>
                    </header>
                    <div className="filter-content collapse show" id="collapse33">
                        <div className="card-body">
                            <div className="card-body">
                                <div data-prop={param.code} id="slider"></div>
                            </div>
                            <hr/>
                            <div className="form-row">
                                <div className="form-group col-md-6">
                                    <label id="slider-limit-value-from" data-total={param.min.total}>
                                        {param.min.displayed}
                                    </label>
                                </div>
                                <div className="form-group text-right col-md-6">
                                    <label id="slider-limit-value-to" data-total={param.max.total}>
                                        {param.max.displayed}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            )
        );
    }
}


class FilterSelectParams extends React.Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            this.props.selectParams.map((param) =>
                <article key={param.code} className="card-group-item">
                    <header className="card-header">
                        <h6 className="title">{this.props.references.properties[param.code].title}</h6>
                    </header>
                    <div className="filter-content collapse show" id="collapse44">
                        <div className="card-body">
                            <form>
                                {param["values"].map((value) =>
                                    <label key={value.value} className="form-check">
                                        <input className="form-check-input" value=""
                                               name={param.code + '_' + value.value} type="checkbox"
                                               disabled={value.count === 0}
                                               onChange={this.props.filterSelectCheckboxHandle} checked={value.selected} />
                                        <span className="form-check-label">
                                                                    <span
                                                                        className="float-right badge badge-light round">{value.count}</span>
                                            {this.props.references.properties[param.code]["values"] ?
                                                this.props.references["properties"][param.code]["values"][value.value]["title"] :
                                                value.value
                                            }
                                                                </span>
                                    </label>
                                )}
                            </form>
                        </div>
                    </div>
                </article>
            )
        )
    }
}

var element = document.getElementById("catalog-component");
ReactDOM.render(
    <Catalog
        api={element.dataset.api}
        filter={element.dataset.filter}
        references={element.dataset.references}
        debug={true}
    />,
    element
);

