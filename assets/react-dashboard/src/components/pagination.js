import React, { Component } from "react";

const defaultProps = {
    initialPage: 1,
    filterValue: ""
}
class Pagination extends Component {
    constructor(props) {
        super(props);
        this.state = { pager: {} };
    }

    componentWillMount() {
        if ( this.props.items && this.props.items.length ) {
            this.setPage( this.props.initialPage );
        }
    }

    componentDidUpdate(prevProps, prevState) {
        const changeItem = (this.props.items.length !== prevProps.items.length);
        const changefilter = (this.props.filterValue !== prevProps.filterValue);
        if ( changeItem || changefilter ) {
            this.setPage( this.props.initialPage );
        }
    }

    setPage(currentPage) {
        var items = this.props.items;
        var pager = this.state.pager;

        if (currentPage < 1 || currentPage > pager.totalPages) {
            return;
        }
        pager = this.getPager(items.length, currentPage);
        var pageItems = items.slice(pager.startIndex, pager.endIndex + 1);
        this.setState({ pager: pager });
        this.props.onChangePage(pageItems);
    }

    getPager(totalItems, currentPage) {
        currentPage = currentPage || 1;
        var pageSize = this.props.pageSize || 10;
        var totalPages = Math.ceil(totalItems / pageSize);

        var startPage, endPage;
        if (totalPages <= 10) {
            startPage = 1;
            endPage = totalPages;
        } else {
            if (currentPage <= 6) {
                startPage = 1;
                endPage = 10;
            } else if (currentPage + 4 >= totalPages) {
                startPage = totalPages - 9;
                endPage = totalPages;
            } else {
                startPage = currentPage - 5;
                endPage = currentPage + 4;
            }
        }
        var startIndex = (currentPage - 1) * pageSize;
        var endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);
        var pages = [...Array((endPage + 1) - startPage).keys()].map(i => startPage + i);

        return {
            totalItems: totalItems,
            currentPage: currentPage,
            pageSize: pageSize,
            totalPages: totalPages,
            startPage: startPage,
            endPage: endPage,
            startIndex: startIndex,
            endIndex: endIndex,
            pages: pages
        };
    }

    render() {
        var pager = this.state.pager;

        if (!pager.pages || pager.pages.length <= 1) {
            return null;
        }

        return (
            <div className="wpneo-pagination">
                <ul className="page-numbers">
                    <li className={pager.currentPage === 1 ? 'disabled' : ''}>
                        <a href="javascrpt:void(0)" onClick={() => this.setPage(1)}>First</a>
                    </li>
                    <li className={pager.currentPage === 1 ? 'disabled' : ''}>
                        <a href="javascrpt:void(0)" onClick={() => this.setPage(pager.currentPage - 1)}>Previous</a>
                    </li>
                    {pager.pages.map((page, index) =>
                        <li key={index}>
                            { pager.currentPage === page ?
                                <span aria-current="page" className="page-numbers current">{page}</span> :
                                <a className="page-link" href="javascrpt:void(0)" onClick={ e => this.setPage(page) }>{page}</a> 
                            }
                        </li>
                    )}
                    <li className={pager.currentPage === pager.totalPages ? 'disabled' : ''}>
                        <a href="javascrpt:void(0)" onClick={() => this.setPage(pager.currentPage + 1)}>Next</a>
                    </li>
                    <li className={pager.currentPage === pager.totalPages ? 'disabled' : ''}>
                        <a href="javascrpt:void(0)" onClick={() => this.setPage(pager.totalPages)}>Last</a>
                    </li>
                </ul>
            </div>
        );
    }
}

Pagination.defaultProps = defaultProps;

export default Pagination;
