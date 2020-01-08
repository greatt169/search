class Catalog extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isWithSpinner: true,
            isHasError: false,
            errorMsg: "Something went wrong!",
            emptyMsg: "No results!",
            result: {total: 0},
            rangeParams: [],
            selectParams: [],
            filterParams: props.filter
        };

        this.addSpinner = this.addSpinner.bind(this);
        this.removeSpinner = this.removeSpinner.bind(this);
        this.reload = this.reload.bind(this);
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
                    if (result.total > 0) {
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

    render() {

        const isWithSpinner = this.state.isWithSpinner;
        const isHasError = this.state.isHasError;
        const errorMsg = this.state.errorMsg;
        const emptyMsg = this.state.emptyMsg;
        const result = this.state.result;
        const references = JSON.parse(this.props.references);
        const rangeParams = this.state.rangeParams;
        const selectParams = this.state.selectParams;
        let spinnerClassName;

        spinnerClassName = isWithSpinner ? "center spinner-border" : "";

        return (
            <div className={`row ${spinnerClassName}`}>
                {isHasError &&
                <div className='alert alert-danger'>{errorMsg.toString()}</div>
                }
                {result.total === 0 ? (
                        <div className='alert alert-primary'>{emptyMsg.toString()}</div>
                    ) :
                    (
                        <div className="row">
                            <aside className="col-sm-3">
                                <div className="card card-filter">
                                    <FilterRangeParams rangeParams={rangeParams} references={references}/>
                                    <FilterSelectParams selectParams={selectParams} references={references}/>
                                </div>
                            </aside>
                            <ItemsList result={result}/>
                        </div>
                    )}
            </div>
        )
    }
}

function ItemsList(props) {
    return (
        <main className="col-sm-9">
            {props.result["items"].map((item) =>
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
        </main>
    );
}

function FilterRangeParams(props) {
    return (
        props.rangeParams.map((param) =>
            <article key={param.code} className="card-group-item">
                <header className="card-header">
                    <h6 className="title">{props.references.properties[param.code].title} </h6>
                </header>
                <div className="filter-content collapse show" id="collapse33">
                    <div className="card-body">
                        <input type="range" className="custom-range"
                               min={`${param.min.selected}`}
                               max={`${param.max.selected}`} name=""/>
                        <div className="form-row">
                            <div className="form-group col-md-6">
                                <label>min</label>
                                <input className="form-control"
                                       placeholder={`${param.min.displayed}`}
                                       type="number"/>
                            </div>
                            <div className="form-group text-right col-md-6">
                                <label>max</label>
                                <input className="form-control"
                                       placeholder={`${param.max.displayed}`}
                                       type="number"/>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        )
    );
}

function FilterSelectParams(props) {
    return (
        props.selectParams.map((param) =>
            <article key={param.code} className="card-group-item">
                <header className="card-header">
                    <h6 className="title">{props.references.properties[param.code].title}</h6>
                </header>
                <div className="filter-content collapse show" id="collapse44">
                    <div className="card-body">
                        <form>
                            {param["values"].map((value) =>
                                <label key={value.value} className="form-check">
                                    <input className="form-check-input" value=""
                                           type="checkbox"/>
                                    <span className="form-check-label">
                                                                    <span
                                                                        className="float-right badge badge-light round">{value.count}</span>
                                        {props.references.properties[param.code]["values"] ?
                                            props.references["properties"][param.code]["values"][value.value]["title"] :
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
    );
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