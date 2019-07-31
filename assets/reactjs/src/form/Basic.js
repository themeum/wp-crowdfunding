import React, { Component } from 'react'

class Basic extends Component {
  render () {
    return (
      <div className='wpcf-accordion-wrapper'>

        <div className='wpcf-accordion active'>
          <div className='wpcf-accordion-title'>Campaign Information</div>
          <div className='wpcf-accordion-details'>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Category</div>
              <div className='wpcf-field-subtitle'>Choose the Category That Most closely aligns with your project</div>
              <div className='wpcf-field-input'>
                <select defaultValue={'saab'} >
                  <option value='volvo'>Cat 1</option>
                  <option value='saab'>Cat 2</option>
                  <option value='vw'>Cat 3</option>
                </select>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Campaign Sub-Catagory</div>
              <div className='wpcf-field-subtitle'>Reach a more specific community by also choosing a subcategory</div>
              <div className='wpcf-field-input'>
                <input type='radio' name='gender' value='individual' />Individual<br />
                <input type='radio' name='gender' value='business' />Business<br />
                <input type='radio' name='gender' value='non-profit' />Non-Profit
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Country *</div>
              <div className='wpcf-field-input'>
                <select defaultValue={'volvo'} >
                  <option value='volvo'>Bangladesh</option>
                  <option value='saab'>Austria</option>
                  <option value='vw'>France</option>
                </select>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>City *</div>
              <div className='wpcf-field-input'>
                <select defaultValue={'saab'} >
                  <option value='volvo'>Bangladesh</option>
                  <option value='saab'>Austria</option>
                  <option value='vw'>France</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div className='wpcf-accordion'>
          <div className='wpcf-accordion-title'>Details</div>
          <div className='wpcf-accordion-details'>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Campaign Title *</div>
              <div className='wpcf-field-subtitle'>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</div>
              <div className='wpcf-field-input'>
                <input type='text' defaultValue='Sample Title' />
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Campaign Sub-Title</div>
              <div className='wpcf-field-subtitle'>Use Words People Might Search For..</div>
              <div className='wpcf-field-input'>
                <input type='text' defaultValue='Sample Sub Title' />
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Campaign Description *</div>
              <div className='wpcf-field-subtitle'>Keep It Short. Just Small Brief About your Project</div>
              <div className='wpcf-field-input'>
                <textarea defaultValue={'This is Description'} />
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Tags</div>
              <div className='wpcf-field-subtitle'>Reach a more specific community by also choosing right Tags. Max Tag : 20</div>
              <div className='wpcf-field-input'>
                <input type='text' defaultValue='Sample Sub Title' />
              </div>
              <div className='wpcf-field-preview'>
                <span>+ Mobile</span>
                <span>+ Application</span>
                <span>+ Development</span>
              </div>
            </div>
          </div>
        </div>

        <div className='wpcf-accordion'>
          <div className='wpcf-accordion-title'>Media</div>
          <div className='wpcf-accordion-media'>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Video</div>
              <div className='wpcf-field-subtitle'>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</div>
              <div className='wpcf-field-input'>
                <button>Upload Video</button>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Video Upload</div>
              <div className='wpcf-field-subtitle'>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</div>
              <div className='wpcf-field-input'>
                <input type='text' defaultValue='' />
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Image Upload *</div>
              <div className='wpcf-field-subtitle'>Dimention Should be 560x340px ; Max Size : 5MB</div>
              <div className='wpcf-field-input'>
                <button>+ Add More Image</button>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Funding Goals *</div>
              <div className='wpcf-field-subtitle'>Lorem ipsum dolor sit amet, consectetur adipiscing</div>
              <div className='wpcf-field-input'>
                <input type='range' min='100' max='500' step='10' />
                <input type='number' min='11' max='10000' defaultValue='' />
                <span>$</span>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Funding Type *</div>
              <div className='wpcf-field-subtitle'>Lorem ipsum dolor sit amet, consectetur adipiscing</div>
              <div className='wpcf-field-input'>
                <input type='radio' name='drone' defaultValue='one' /><span>Fixed Funding</span><span>Accept All or Nothing</span>
                <input type='radio' name='drone' defaultValue='two' /><span>Flexible Funding</span><span>Accept if doesnot meet goal</span>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Goal Type *</div>
              <div className='wpcf-field-subtitle'>Lorem ipsum dolor sit amet, consectetur adipiscing</div>
              <div className='wpcf-field-input'>
                <input type='radio' name='drone2' defaultValue='one' />Target Goal
                <input type='radio' name='drone2' defaultValue='two' />Target Date
                <input type='radio' name='drone2' defaultValue='three' />Campaign Never End
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Amount Range *</div>
              <div className='wpcf-field-subtitle'>You can Fixed a Maximum and Minimum Amount</div>
              <div className='wpcf-field-input'>
                <input type='text' defaultValue='10-20' /><span>$</span>
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Recommended Amount *</div>
              <div className='wpcf-field-subtitle'>You can Fixed a Maximum Amount</div>
              <div className='wpcf-field-input'>
                <span>$10</span><span>$20</span><span>$30</span><span>$40</span>
                <input type='text' defaultValue='10-20' /><span>$</span>
              </div>
            </div>

          </div>
        </div>

        <div className='wpcf-accordion'>
          <div className='wpcf-accordion-title'>Contributor</div>
          <div className='wpcf-accordion-contributor'>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Contributor Table</div>
              <div className='wpcf-field-subtitle'>You can make contributors table</div>
              <div className='wpcf-field-input'>
                <input type='checkbox' value='show' /> Show contributor table on campaign single page
              </div>
            </div>
            <div className='wpcf-form-field'>
              <div className='wpcf-field-title'>Contributor Anonymity</div>
              <div className='wpcf-field-subtitle'>You can make contributors anonymus visitors will not see the backers</div>
              <div className='wpcf-field-input'>
                <input type='checkbox' value='show' />Make contributors anonymous on the contributor table
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }
}
export default Basic
