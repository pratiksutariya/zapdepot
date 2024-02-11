<template>
  <div>
    <loader :active="loader" />
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Category </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a>Home</a>
              </li>
              <li class="breadcrumb-item active">Zaps</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline" style="background-color: #e0e4e6">
              <div class="card-header">
                <div class="css-1vs3tn1">
                  <div
                    aria-label="Name your zap"
                    role="button"
                    tabindex="0"
                    data-css-reset="true"
                    class="css-k5u3tc-index--isEditing"
                  >
                    <div
                      role="button"
                      tabindex="-1"
                      data-css-reset="true"
                      class="css-1fc2kbd-index__iconContainer"
                    >
                      <svg
                        aria-labelledby="edit-icon-1647511007000.635"
                        class="edit-icon-animated css-voy20m"
                        height="20"
                        viewBox="0 0 24 24"
                        width="20"
                      >
                        <title id="edit-icon-1647511007000.635" />
                        <path
                          d="M20.71 5.63006C21.1 6.02006 21.1 6.65006 20.71 7.04006L18.88 8.87006L15.13 5.12006L16.96 3.29  006C17.35 2.90006 17.98 2.90006 18.37 3.29006L20.71 5.63006ZM3 21.0001V17.2501L14.06 6.19006L17.81 9.94006L6.75 21.0001H3Z"
                        />
                      </svg>
                    </div>
                    <div data-css-reset="true" class="css-q0uaac-index__contentWrap">
                      <span class="css-1l8ki72">
                        <input
                          type="text"
                          class="form-control custom-input"
                          id="zap_title_input"
                          @input="updateName"
                          v-model="zap.name"
                        />
                      </span>
                    </div>
                    <button type="button" class="btn btn-info">
                      <router-link class="history-view"
                              :to="{
                                name: 'Log',
                              }"
                            >
                              History
                            </router-link>
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body py-0">
                <div class="row justify-content-center">
                  <div class="main-box col-12">
                    <div class="main-object col-11 mt-5">
                      <div data-css-reset="true" class="flex-object col-12">
                        <div class="content-flex">
                          <div class="content-logo d-flex align-items-center">
                            <div class="logo p-3 mr-3" :class="sender_active_tab ? 'green-back' : ''">
                              <svg
                                width="30"
                                height="30"
                                :class="sender_active_tab ? '' : 'Content-logo-box'"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M2.02002 12C2.02002 6.49002 6.49002 2.02002 12 2.02002C17.51 2.02002 21.98 6.49002 21.98 12C21.98 17.51 17.51 21.98 12 21.98C6.49002 21.98 2.02002 17.51 2.02002 12ZM11.48 13.74V20L16.35 10.26H13V4.00002L8.00002 13.74H11.48Z"
                                  :fill="sender_active_tab ? '#fff' : '#666666'"
                                />
                              </svg>
                            </div>
                            <div class="text-flex">
                              <div class="text-one">1. Trigger</div>
                              <div class="text-two">A trigger is an event that starts your Zap</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="make-line col-12">
                    <div class="line"></div>
                  </div>
                  <div class="main-box col-12" v-if="showTrigger">
                    <div class="main-object col-11">
                      <div data-css-reset="true" class="flex-object-open col-12">
                        <div class="content-flex d-flex">
                          <div class="content-logo d-flex align-items-center">
                            <div class="logo-open p-3 mr-3">
                              <svg
                                width="30"
                                height="30"
                                class="Content-logo-box-open"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M2.02002 12C2.02002 6.49002 6.49002 2.02002 12 2.02002C17.51 2.02002 21.98 6.49002 21.98 12C21.98 17.51 17.51 21.98 12 21.98C6.49002 21.98 2.02002 17.51 2.02002 12ZM11.48 13.74V20L16.35 10.26H13V4.00002L8.00002 13.74H11.48Z"
                                  fill="#fff"
                                />
                              </svg>
                            </div>
                            <div class="text-flex">
                              <div class="text-one-open">Trigger</div>
                              <div class="text-one-open">
                                An action is an event a Zap performs after it
                                starts
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="main-content">
                        <div class="content-color">
                          <div class="content-box">
                            <div class="main-content">
                              <div class="logo-flex-two d-flex">
                                <div class="logo-app d-inline-flex">
                                  <div class="for-padding d-inline-flex m-2">
                                    <svg
                                      width="35"
                                      height="35"
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M4 8H8V4H4V8ZM10 20H14V16H10V20ZM8 20H4V16H8V20ZM4 14H8V10H4V14ZM14 14H10V10H14V14ZM16 4V8H20V4H16ZM14 8H10V4H14V8ZM16 14H20V10H16V14ZM20 20H16V16H20V20Z"
                                        fill="#FFBD59"
                                      />
                                    </svg>
                                  </div>
                                </div>
                                <div class="main-text-line">
                                  <div class="ones-text">App Event</div>
                                  <div class="two-text">
                                    Start the Zap when something happens in an
                                    app
                                  </div>
                                </div>
                              </div>
                              <div class="logo-box">
                                <div class="logo-flex-box">
                                  <div class="work-box">
                                    <div
                                      class="logo-box-one"
                                      :class="
                                        zap.sender_name == 'gohighlevel'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('gohighlevel')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/ready-funnels-small.png"
                                        />
                                      </div>
                                      <div>Ready Funnels Pro</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'active_campaign'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('active_campaign')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/activec.png"
                                        />
                                      </div>
                                      <div>Active Campaign</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'gohighlevel_single'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('gohighlevel_single')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/gohighlevel.png"
                                        />
                                      </div>
                                      <div>Gohighlevel</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'webinar_account'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('webinar_account')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/webinar.png"
                                        />
                                      </div>
                                      <div>GoToWebinar</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'google_sheet'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('google_sheet')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/Sheets.png"
                                        />
                                      </div>
                                      <div>Google Sheet</div>
                                    </div>
                                    <!-- <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'web_hook'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('web_hook')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/webhook.svg"
                                        />
                                      </div>
                                      <div>WebHooks</div>
                                    </div> -->
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'aweber'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_sender('aweber')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/aweber_2.png"
                                        />
                                      </div>
                                      <div>AWeber</div>
                                    </div>
                                    <!-- ho -->
                                  </div>
                                </div>
                              </div>
                              <!-- {{ zap.sender_tag_list_id.length }} -->
                              <GoHighLevel
                                v-if="zap.sender_name == 'gohighlevel'"
                                :getGohighLevelAccounts="getGohighLevelAccounts"
                                :selected_account_id="zap.sender_id"
                                :selected_tag_list_id="zap.sender_tag_list_id"
                                event_type="trigger"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <GoHighLevelSingle
                                v-if="zap.sender_name == 'gohighlevel_single'"
                                :getGohighLevelAccountsSingle="
                                  getGohighLevelAccountsSingle
                                "
                                :selected_account_id="zap.sender_id"
                                :selected_tag_list_id="zap.sender_tag_list_id"
                                event_type="trigger"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <ActiveCampaign
                                v-if="zap.sender_name == 'active_campaign'"
                                :getActiveCamAccounts="getActiveCamAccounts"
                                :selected_account_id="zap.sender_id"
                                event_type="trigger"
                                :action_type="zap.sender_action_type"
                                :selected_tag_list_id="zap.sender_tag_list_id"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                                @set_action_type="set_action_type"
                              />
                              <!-- {{ zap }} -->
                              <webinarAccount
                                v-if="zap.sender_name == 'webinar_account'"
                                :getWebinarsAccounts="getWebinarsAccounts"
                                :selected_account_id="zap.sender_id"
                                event_type="trigger"
                                :action_type="zap.sender_action_type"
                                :selected_tag_list_id="zap.sender_tag_list_id"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <GoogleSheet
                               v-if="zap.sender_name == 'google_sheet'"
                               :allGoogleAccounts="allGoogleAccounts"
                               :selected_account_id="zap.sender_id"
                               event_type="trigger"
                               action_type="zap.sender_action_type"
                               @set_account_id="set_account_id"
                               :selected_tag_list_id="zap.sender_tag_list_id"
                               @set_tag_list_id="set_tag_list_id"
                              />
                              <webHook
                               v-if="zap.sender_name == 'web_hook'"
                               :webhook_url="webhooks_url"
                               :selected_account_id="zap.sender_id"
                               :zap_id="zap.id"
                               event_type="trigger"
                               action_type="zap.sender_action_type"
                               @set_tag_list_id="set_tag_list_id"
                               @set_account_id="set_account_id"
                              />
                              <AWeber
                                v-if="zap.sender_name == 'aweber'"
                                :getWebinarsAccounts="getWebinarsAccounts"
                                :selected_account_id="zap.sender_id"
                                event_type="trigger"
                                :action_type="zap.sender_action_type"
                                :selected_tag_list_id="zap.sender_tag_list_id"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <div>
                                <!-- <div class="bold-make text-center">…and over 4,700+ more
                                </div>-->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div
                    class="plus-btn cursor-pointer"
                    id="trigger-button"
                    v-if="!showTrigger"
                    @click="showTrigger = true"
                    >
                    <div class="plus-box" style="height: 26px">
                      <svg
                        style="background: white; border-radius: 50%"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z" fill="#666666" />
                      </svg>
                    </div>
                  </div> -->
                  <div class="make-line col-12">
                    <div class="line"></div>
                  </div>
                  <div class="d-flex">
                    <div aria-hidden="true" class="css-pux7cs">▼</div>
                  </div>
                  <div class="main-box col-12">
                    <div class="main-object col-11">
                      <div data-css-reset="true" class="flex-object col-12">
                        <div class="content-flex">
                          <div class="content-logo d-flex align-items-center">
                            <div class="logo p-3 mr-3" :class="receiver_active_tab ? 'green-back' : ''">
                              <svg
                                width="30"
                                height="30"
                                :class="receiver_active_tab ? '' : 'Content-logo-box'"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M2.02002 12C2.02002 6.49002 6.49002 2.02002 12 2.02002C17.51 2.02002 21.98 6.49002 21.98 12C21.98 17.51 17.51 21.98 12 21.98C6.49002 21.98 2.02002 17.51 2.02002 12ZM11.48 13.74V20L16.35 10.26H13V4.00002L8.00002 13.74H11.48Z"
                                  :fill="receiver_active_tab ? '#fff' : '#666666'"
                                />
                              </svg>
                            </div>
                            <div class="text-flex">
                              <div class="text-one">2. Action</div>
                              <div class="text-two">A Action is an event that starts your Zap</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="make-line col-12">
                    <div class="line"></div>
                  </div>
                  <div class="main-box col-12" id="action-box" v-if="showAction">
                    <div class="main-object col-11">
                      <div data-css-reset="true" class="flex-object-open col-12">
                        <div class="content-flex d-flex">
                          <div class="content-logo d-flex align-items-center">
                            <div class="logo-open p-3 mr-3">
                              <svg
                                width="30"
                                height="30"
                                class="Content-logo-box-open"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M2.02002 12C2.02002 6.49002 6.49002 2.02002 12 2.02002C17.51 2.02002 21.98 6.49002 21.98 12C21.98 17.51 17.51 21.98 12 21.98C6.49002 21.98 2.02002 17.51 2.02002 12ZM11.48 13.74V20L16.35 10.26H13V4.00002L8.00002 13.74H11.48Z"
                                  fill="#fff"
                                />
                              </svg>
                            </div>
                            <div class="text-flex">
                              <div class="text-one-open">Action</div>
                              <div class="text-one-open">
                                An action is an event a Zap performs after it
                                starts
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="main-content">
                        <div class="content-color">
                          <div class="content-box">
                            <div class="main-content">
                              <div class="logo-flex-two d-flex">
                                <div class="logo-app d-inline-flex">
                                  <div class="for-padding d-inline-flex m-2">
                                    <svg
                                      width="35"
                                      height="35"
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M4 8H8V4H4V8ZM10 20H14V16H10V20ZM8 20H4V16H8V20ZM4 14H8V10H4V14ZM14 14H10V10H14V14ZM16 4V8H20V4H16ZM14 8H10V4H14V8ZM16 14H20V10H16V14ZM20 20H16V16H20V20Z"
                                        fill="#FFBD59"
                                      />
                                    </svg>
                                  </div>
                                </div>
                                <div class="main-text-line">
                                  <div class="ones-text">App Event</div>
                                  <div class="two-text">
                                    Start the Zap when something happens in an
                                    app
                                  </div>
                                </div>
                              </div>
                              <div class="logo-box">
                                <div class="logo-flex-box">
                                  <div class="work-box">
                                    <div
                                      class="logo-box-one"
                                      :class="
                                        zap.receiver_name == 'gohighlevel'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('gohighlevel')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/ready-funnels-small.png"
                                        />
                                      </div>
                                      <div>Ready Funnels Pro</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.receiver_name == 'active_campaign'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('active_campaign')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/activec.png"
                                        />
                                      </div>
                                      <div>Active Campaign</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.receiver_name ==
                                        'gohighlevel_single'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="
                                        set_receiver('gohighlevel_single')
                                      "
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/gohighlevel.png"
                                        />
                                      </div>
                                      <div>Gohighlevel</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.receiver_name == 'webinar_account'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('webinar_account')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/webinar.png"
                                        />
                                      </div>
                                      <div>GoToWebinar</div>
                                    </div>
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.receiver_name == 'google_sheet'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('google_sheet')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/Sheets.png"
                                        />
                                      </div>
                                      <div>Google Sheet</div>
                                    </div>

                                    <!-- <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.receiver_name == 'google_sheet'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('google_sheet')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/Sheets.png"
                                        />
                                      </div>
                                      <div>webHook</div>
                                    </div> -->
                                    <div
                                      class="logo-box-one ml-2"
                                      :class="
                                        zap.sender_name == 'aweber'
                                          ? 'active'
                                          : ''
                                      "
                                      @click="set_receiver('aweber')"
                                    >
                                      <div>
                                        <img
                                          class="img-set"
                                          width="30"
                                          height="30"
                                          src="@/assets/images/aweber_2.png"
                                        />
                                      </div>
                                      <div>AWeber</div>
                                    </div>
                                    <!-- ho -->
                                  </div>
                                </div>
                              </div>
                              <GoHighLevel
                                v-if="zap.receiver_name == 'gohighlevel'"
                                :getGohighLevelAccounts="getGohighLevelAccounts"
                                :selected_account_id="zap.receiver_id"
                                :selected_tag_list_id="zap.receiver_tag_list_id"
                                event_type="action"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <GoHighLevelSingle
                                v-if="zap.receiver_name == 'gohighlevel_single'"
                                :getGohighLevelAccountsSingle="
                                  getGohighLevelAccountsSingle
                                "
                                :selected_account_id="zap.receiver_id"
                                :selected_tag_list_id="zap.receiver_tag_list_id"
                                event_type="action"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <ActiveCampaign
                                v-if="zap.receiver_name == 'active_campaign'"
                                :getActiveCamAccounts="getActiveCamAccounts"
                                :selected_account_id="zap.receiver_id"
                                event_type="action"
                                :action_type="zap.receiver_action_type"
                                :selected_tag_list_id="zap.receiver_tag_list_id"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                                @set_action_type="set_action_type"
                              />
                              <webinarAccount
                                v-if="zap.receiver_name == 'webinar_account'"
                                :getWebinarsAccounts="getWebinarsAccounts"
                                :selected_account_id="zap.receiver_id"
                                event_type="action"
                                :action_type="zap.receiver_action_type"
                                :selected_tag_list_id="zap.receiver_tag_list_id"
                                @set_tag_list_id="set_tag_list_id"
                                @set_account_id="set_account_id"
                              />
                              <GoogleSheet
                               v-if="zap.receiver_name == 'google_sheet'"
                               :allGoogleAccounts="allGoogleAccounts"
                               :selected_account_id="zap.receiver_id"
                               event_type="action"
                               action_type="zap.receiver_action_type"
                               :selected_tag_list_id="zap.receiver_tag_list_id"
                               @set_tag_list_id="set_tag_list_id"
                               @set_account_id="set_account_id"
                              />
                              <!-- <webHook
                               v-if="zap.receiver_name == 'web_hook'"
                               :allGoogleAccounts="allGoogleAccounts"
                               :selected_account_id="zap.receiver_id"
                               event_type="action"
                               action_type="zap.receiver_action_type"
                               :selected_tag_list_id="zap.receiver_tag_list_id"
                               @set_tag_list_id="set_tag_list_id"
                               @set_account_id="set_account_id"
                              /> -->
                              <div></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    class="plus-btn cursor-pointer"
                    id="action-button"
                    v-if="!showAction"
                    @click="showAction = true"
                  >
                    <div class="plus-box" style="height: 26px">
                      <svg
                        style="background: white; border-radius: 50%"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z" fill="#666666" />
                      </svg>
                    </div>
                  </div>
                  <!-- <div class="make-line col-12">
                    <div class="line"></div>
                  </div>
                  <div class="d-flex">
                    <div aria-hidden="true" class="css-pux7cs">▼</div>
                  </div> -->
                </div>
              </div>
              <div class="card-footer mt-3 d-flex justify-content-end">
                <button class="btn btn-primary" @click="updateZapData">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import _ from "lodash";
import { defineComponent } from "vue";
import { mapGetters, mapActions } from "vuex";
import GoHighLevel from "@/components/integration/gohighlevel.vue";
import ActiveCampaign from "@/components/integration/activeCampaign.vue";
import GoHighLevelSingle from "@/components/integration/gohighlevelSingle.vue";
import webinarAccount from "@/components/integration/webinarAccount.vue";
import GoogleSheet from "@/components/integration/googleSheet.vue";
import webHook from "@/components/integration/webHook.vue";
import AWeber from "@/components/integration/AWeber.vue";
import { useToast } from "vue-toastification";

export default defineComponent({
  setup() {
    const toast = useToast();
    return {
      toast
    };
  },
  components: {
    GoHighLevel,
    ActiveCampaign,
    GoHighLevelSingle,
    webinarAccount,
    GoogleSheet,
    webHook,
    AWeber
  },
  data() {
    return {
      zap: {
        name: "",
        sender_name: "",
        receiver_name: "",
        receiver_id: "",
        sender_id: "",
        sender_tag_list_id: "",
        sender_action_type: "",
        receiver_tag_list_id: "",
        receiver_action_type: ""
      },
      loader: false,
      showAction: false,
      showTrigger: true,
      // showTrigger: false,
    };
  },
  computed: {
    ...mapGetters({
      getSelectedZap: "zap/getZap",
      getGohighLevelAccounts: "integration/getGohighLevelAccounts",
      getGohighLevelAccountsSingle: "integration/getGohighLevelAccountsSingle",
      getActiveCamAccounts: "integration/getActiveCamAccounts",
      getWebinarsAccounts: "integration/getWebinarAccounts",
      allGoogleAccounts: "integration/getGoogleAccounts",
      // getWebHookurl: "integration/getWebHookurl",
    }),
    sender_active_tab(){
      if(this.zap.sender_name == 'gohighlevel_single')
      {
        return (this.zap && this.zap.sender_id) ? true : false
      }else{
        return (this.zap && this.zap.sender_tag_list_id && this.zap.sender_tag_list_id.length) ? true : false
      }
    },
    receiver_active_tab(){
      if(this.zap.receiver_name == 'gohighlevel_single')
      {
        return (this.zap && this.zap.receiver_id) ? true : false
      }else{
        return (this.zap && this.zap.receiver_tag_list_id && this.zap.receiver_tag_list_id.length) ? true : false
      }
    }
  },
  watch:{
    'zap.sender_tag_list_id':{
      handler(){
        this.showAction = false
        if(this.zap.sender_name != 'gohighlevel_single'){
          this.showAction = (this.zap && this.zap.sender_tag_list_id && this.zap.sender_tag_list_id.length) ? true : false
        }
      },deep:true
    },
    'zap.sender_id':{
      handler(){
        this.showAction = false
        if(this.zap.sender_name == 'gohighlevel_single'){
          this.showAction = (this.zap && this.zap.sender_id) ? true : false
        }
      },deep:true
    }
  },
  methods: {
    ...mapActions({
      updateZapName: "zap/updateZapName",
      SetSelectZap: "zap/SetSelectZap",
      updateZap: "zap/updateZap",
      setgohilevelAccount: "integration/getAllGohilevelAccounts",
      setgohilevelAccountSingle: "integration/setAllGohilevelAccountsSingle",
      setActiveCamAccounts: "integration/getAllActiveCamAccounts",
      setAllWebinarData: "integration/getAllWebinarData",
      getAllGoogleDATA: "integration/getAllGoogleDATA",
      // getWebHook: "integration/getWebHook"
    }),
    updateName: _.debounce(function() {
      this.updateNameA();
    }, 500),
    updateNameA() {
      if (this.zap.name) {
        let data = {
          id: this.zap.id,
          name: this.zap.name
        };
        console.log(data);
        this.updateZapName(data)
          .then(() => {})
          .catch(() => {});
      }
    },
    async getzapData(id) {
      this.loader = true;
      await this.SetSelectZap(id)
        .then(response => {
          this.loader = false;
          this.zap = response.data.data;
          if (this.zap.sender_name == "gohighlevel") {
            this.showTrigger = true;
            this.getGohighlevelAccouts();
          } else if (this.zap.sender_name == "active_campaign") {
            this.showTrigger = true;
            this.getActiveCamAccouts();
          } else if (this.zap.sender_name == "gohighlevel_single") {
            this.showTrigger = true;
            this.getGohighlevelAccoutsSingle();
          } else if (this.zap.sender_name == "webinar_account") {
            this.showTrigger = true;
            this.getAllWebinarAccountData();
          }else if (this.zap.sender_name == "google_sheet") {
            this.showTrigger = true;
            this.getAllGoogleAccounts();
          }else if (this.zap.sender_name == "web_hook") {
            this.showTrigger = true;
            // this.getAllGoogleAccounts();
          }
          if (this.zap.receiver_name == "gohighlevel") {
            this.showAction = true;
            this.getGohighlevelAccouts();
          } else if (this.zap.receiver_name == "active_campaign") {
            this.showAction = true;
            this.getActiveCamAccouts();
          } else if (this.zap.receiver_name == "gohighlevel_single") {
            this.showAction = true;
            this.getGohighlevelAccoutsSingle();
          } else if (this.zap.receiver_name == "webinar_account") {
            this.showAction = true;
            this.getAllWebinarAccountData();
          } else if (this.zap.receiver_name == "google_sheet") {
            this.showAction = true;
            this.getAllGoogleAccounts();
          }
        })
        .catch(() => {
          this.loader = false;
        });
    },
    set_sender(data) {
      (this.zap.sender_id = ""),
        (this.zap.sender_tag_list_id = ""),
        (this.zap.sender_action_type = ""),
        (this.zap.sender_name = data);
      if (data == "gohighlevel") {
        this.getGohighlevelAccouts();
      } else if (data == "active_campaign") {
        this.getActiveCamAccouts();
      } else if (data == "gohighlevel_single") {
        this.getGohighlevelAccoutsSingle();
      } else if (data == "webinar_account") {
        this.getAllWebinarAccountData();
      } else if (data == "google_sheet") {
        this.getAllGoogleAccounts();
      }
    },
    set_receiver(data) {
      (this.zap.receiver_id = ""),
        (this.zap.receiver_tag_list_id = ""),
        (this.zap.receiver_action_type = ""),
        (this.zap.receiver_name = data);
      if (data == "gohighlevel") {
        this.getGohighlevelAccouts();
      } else if (data == "active_campaign") {
        this.getActiveCamAccouts();
      } else if (data == "gohighlevel_single") {
        this.getGohighlevelAccoutsSingle();
      } else if (data == "webinar_account") {
        this.getAllWebinarAccountData();
      } else if (data == "google_sheet") {
        this.getAllGoogleAccounts();
      }
    },
    getGohighlevelAccouts() {
      this.loader = true;
      this.setgohilevelAccount()
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    getGohighlevelAccoutsSingle() {
      this.loader = true;
      this.setgohilevelAccountSingle()
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    getActiveCamAccouts() {
      this.loader = true;
      this.setActiveCamAccounts()
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    getAllWebinarAccountData() {
      this.loader = true;
      this.setAllWebinarData()
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    getAllGoogleAccounts() {
      this.loader = true;
      this.getAllGoogleDATA()
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    set_tag_list_id(data) {
      // console.log(data,"dadadad");
      if (data.event_type == "trigger") {
        // console.log("trigger",data.tag_list_id);
        this.zap.sender_tag_list_id = data.tag_list_id;
      } else {
        // console.log("tag_list_id",data.tag_list_id);
        this.zap.receiver_tag_list_id = data.tag_list_id;
      }
    },
    set_account_id(data) {
      // alert(data)
      if (data.event_type == "trigger") {
        this.zap.sender_id = data.account_id;
        // this.zap.sender_action_type = "zap";
      } else {
        this.zap.receiver_id = data.account_id;
        // this.zap.receiver_action_type = "zap";
      }
    },
    set_action_type(data) {
      if (data.event_type == "trigger") {
        this.zap.sender_action_type = data.action_type;
      } else {
        console.log("action_type",data.action_type);
        this.zap.receiver_action_type = data.action_type;
      }
    },
    async updateZapData() {
      console.log(this.zap, "-------------------------",this.zap.sender_tag_list_id);
      console.log(this.zap, "-------------------------",this.zap.receiver_tag_list_id);
      if (!this.zap.sender_id || !this.zap.receiver_id) {
        this.toast.error("Please choose action account and trigger accounts");
        return;
      }
      if (
        this.zap.sender_name == this.zap.receiver_name &&
        this.zap.sender_id == this.zap.receiver_id
      ) {
        this.toast.error(
          "Please choose different accounts in action and trigger"
        );
        return;
      }
      this.loader = true;
      await this.updateZap(this.zap)
        .then(response => {
          if (response.data.status == true) {
            this.toast.success("Updated.");
          }
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    }
  },
  created() {
    if (this.$route.params.id) {
      this.getzapData(this.$route.params.id);
    }
  }
});
</script>

<style scoped>
body {
  background: #e0e4e6;
}

.flex-object {
  border-radius: 10px;
  padding: 18px;
  width: 100%;
  display: flex;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: start;
  justify-content: start;
  background-color: rgb(255, 255, 255);
  border: 1px solid #9cacb4;
}

.main-object {
  margin: 0 auto;
}

.logo {
  display: flex;
  border: 1px solid #e0e4e6;
  border-radius: 30%;
}

.Content-logo-box {
  opacity: 0.2;
}

.text-one {
  font-weight: 600;
  font-size: 20px;
}

.text-two {
  font-weight: 300;
}

.text-one-open {
  color: white;
  font-weight: 600;
  font-size: 20px;
}

.line {
  width: 2px;
  height: 20px;
  background: black;
  margin: 0 auto;
  background: rgb(153, 153, 153);
}

.css-pux7cs {
  margin: -5px;
  color: rgb(153, 153, 153);
}

.logo-open {
  display: flex;
  border: 1px solid white;
  border-radius: 30%;
}

.flex-object-open {
  background: #b11a03 !important;
  border-radius: 10px 10px 0 0;
  padding: 18px;
  width: 100%;
  display: flex;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: start;
  justify-content: start;
  background-color: rgb(255, 255, 255);
  border: 1px solid #9cacb4;
}

.content-color {
  background: rgb(255, 255, 255);
  padding: 20px;
  border-right: 1px solid #9cacb4;
  border-bottom: 1px solid #9cacb4;
  border-left: 1px solid #9cacb4;
  border-radius: 0px 0px 10px 10px;
}

.logo-app {
  border: 1px solid #ffbd59;
  margin-right: 20px;
  border-radius: 10px;
}

.ones-text {
  font-size: 19px;
  font-weight: 600;
}

.text-one-open {
  font-weight: 300;
}

.two-text {
  font-weight: 300;
}

.logo-box {
  padding: 20px 0px;
  margin-top: 20px;
}

.img-set {
  display: inline-flex;
  margin-right: 12px;
}

.work-box {
  width: 100% !important;
  display: flex;
}

.logo-box-one {
  width: 33.33% !important;
  display: flex;
  align-items: center;
  padding: 15px 15px;
  border-radius: 10px;
}

.logo-box-one:hover {
  background: white;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
  transition-duration: 300ms;
  transition-timing-function: ease-in-out;
}

.logo-box-one.active {
  background: white;
  /* box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; */
  box-shadow: rgb(255 8 8) 0px 5px 15px;
  transition-duration: 300ms;
  transition-timing-function: ease-in-out;
}

.content-box {
  background: rgb(250, 250, 250);
  padding: 30px;
  border-radius: 10px;
}

.cursor-pointer {
  cursor: pointer;
}

.css-1btvfpw {
  margin-left: 15px;
  font-size: 1.6rem;
  font-weight: 600;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: auto;
  overflow: hidden;
  opacity: 1;
  transition: opacity 250ms ease-in-out 100ms;
}

.css-1vs3tn1 {
  margin-left: 15px;
  font-size: 1.6rem;
  font-weight: 600;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
  overflow: hidden;
  opacity: 1;
  transition: opacity 250ms ease-in-out 100ms;
}

.css-1bd2nsh-index--canClickText {
  -webkit-box-align: center;
  align-items: center;
  border-bottom: 1px solid transparent;
  display: flex;
}

.css-1bd2nsh-index--canClickText {
  border-bottom-color: rgb(204, 204, 204);
  cursor: pointer;
}

.css-q0uaac-index__contentWrap {
  display: block;
  flex: 1 1 0%;
  overflow: hidden;
  margin-left: 0.4em;
  margin-right: 10px;
}

.css-k5u3tc-index--isEditing {
  border-bottom-color: rgb(19, 107, 245);
}

.css-k5u3tc-index--isEditing {
  -webkit-box-align: center;
  align-items: center;
  border-bottom: 1px solid transparent;
  display: flex;
}

.custom-input {
  background: transparent;
  border: none;
  border-bottom: 1px dashed;
  font-size: 25px;
  padding-left: 0px;
}

.custom-input:focus {
  background: transparent !important;
  border-color: #0500aa;
}
.green-back{
  background: #28A745;
}
.history-view{
  color: white;
}
</style>
