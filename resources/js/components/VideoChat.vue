<template>
  <div class="fullwrap">
    <div class="Sidebar">
      <div class="SearchWrap">
        <div class="SearchUser">
          <form action="">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" name="userprofile" class="formcontrol">
          </form>
        </div>

        <div class="moreInfo" @click="OpenOptionMenu" ref="DropdownRefs">
          <svg xmlns="http://www.w3.org/2000/svg" width="20px" aria-hidden="true" viewBox="0 0 14 14" role="img">
            <path fill-rule="evenodd"
              d="M3.52 6.75c0 .966-.789 1.75-1.76 1.75A1.755 1.755 0 010 6.75C0 5.784.788 5 1.76 5c.971 0 1.76.784 1.76 1.75m10.48 0c0 .966-.788 1.75-1.76 1.75a1.755 1.755 0 01-1.759-1.75c0-.966.788-1.75 1.759-1.75.972 0 1.76.784 1.76 1.75m-5.24 0c0 .966-.788 1.75-1.76 1.75a1.755 1.755 0 01-1.76-1.75C5.24 5.784 6.03 5 7 5c.972 0 1.76.784 1.76 1.75">
            </path>
          </svg>
        </div>

        <div class="dropDownMenu" v-if="isDropdownOpen">
          <ul>
            <li><a @click.prevent="toggleCreateRoom">Create New Room</a></li>
            <li><a href="#">Shortcut keys</a></li>
            <li><a href="#">Message Settings</a></li>
            <li><a href="#">Out of office</a></li>
            <li><a href="#">Configure Integrations</a></li>
          </ul>
        </div>

      </div>
      <div class="ProfileWrap">
        <button type="button" class="mr-2" style="" v-for="user in allusers" :key="user.id"
          @click="placeUserClick(user.id, user.name)">
          <i class="fa fa-user"></i> {{ user.name }}
          <span class="badge" v-html="getUserOnlineStatus(user.id)"></span>
        </button>
        <button type="button" class="mr-2" style="" v-for="group in allgroups" :key="group.id"
          @click="placeGroupDataOnUserClick(group.id, group.group_name)">
          <i class="fa fa-users"></i> {{ group.group_name }}
          <span class="badge" v-html="getUserOnlineStatus(group.id)"></span>
        </button>
      </div>
    </div>

    <div class="ContentArea">
      <div class="ChatHeader">
        <div class="UserProfile" v-if="selectedUserName">
          <span v-html="selectedUserName"></span>
        </div>
        <div class="UserProfile" v-if="selectedGroupName">
          <span v-html="selectedGroupName"></span>
        </div>
        <button v-if="selectedUserID" id="callButton" @click="placeVideoCall(selectedUserID, selectedUserName);"><i
            class="fa fa-video"></i>
        </button>
      </div>

      <div v-html="messageOutput" id="messageOutput">
      </div>

      <div class="ChatFooter" v-if="selectedUserID">
        <form id="chatForm" @submit.prevent="sendMessage">
          <div class="chatFormFooter">
            <div class="chatFormFooterLeft">
              <div class="form-group">
                <input v-model="inputContent" class="WriteMsg" ref="inputField" @focus="handleInputFocus"
                  @blur="handleInputBlur" placeholder="Write a message ..." />
              </div>
            </div>

            <div class="chatFormFooterRight">
              <div class="AttachmentFile">
                <div class="PreviewWrap" v-if="uploaded">
                  <div class="UploadFile">
                    <img class="profile_pic" :src="profilePicSrc" :class="{ uploaded: uploaded }" alt="Profile Picture" />

                    <p>{{ FileName }}</p>

                    <span class="ClearUpload" @click="clearImage"><i class="fa fa-times"></i></span>
                  </div>
                </div>

                <div class="p-image">
                  <i class="fa fa-paperclip upload-button"></i>
                  <input class="file-upload" type="file" id="fileInput" ref="fileInput" accept="image/*"
                    @change="handleFileChange" />
                </div>
              </div>

              <div ref="emojiPicker">
                <div class="emoji_picker" ref="emojiPicker" v-if="isEmojiPickerOpen">
                  <div class="picker_container">
                    <div class="category" v-for="category in categories" :key="`category_${category}`">
                      <span>{{ category }}</span>
                      <div class="emojis_container">
                        <a @click.prevent="handleEmojiClick(emoji)" v-for="(emoji, index) in category_emojis(category)"
                          :key="`emoji_${index}`">
                          {{ emoji }}
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="bottom_arrow" v-if="show_arrow"></div>
                </div>

                <a @click.prevent="toggleEmojiPicker"><i class="fa fa-smile"></i></a>
              </div>

              <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </div>

          </div>
        </form>

        <div id="message"></div>
      </div>

      <div class="ChatFooter" v-if="selectedGroupID">
        <form id="chatForm" @submit.prevent="sendGroupMessage">
          <div class="chatFormFooter">
            <div class="chatFormFooterLeft">
              <div class="form-group">
                <input v-model="inputContent" class="WriteMsg" ref="inputField" @focus="handleInputFocus"
                  @blur="handleInputBlur" placeholder="Write a message ..." />
              </div>
            </div>

            <div class="chatFormFooterRight">

              <div class="AttachmentFile">
                <div class="PreviewWrap" v-if="uploaded">
                  <div class="UploadFile">
                    <img class="profile_pic" :src="profilePicSrc" :class="{ uploaded: uploaded }" alt="Profile Picture" />

                    <p>{{ FileName }}</p>

                    <span class="ClearUpload" @click="clearImage"><i class="fa fa-times"></i></span>
                  </div>
                </div>

                <div class="p-image">
                  <i class="fa fa-paperclip upload-button"></i>
                  <input class="file-upload" type="file" id="fileInput" ref="fileInput" accept="image/*"
                    @change="handleFileChange" />
                </div>
              </div>

              <div ref="emojiPicker">
                <div class="emoji_picker" ref="emojiPicker" v-if="isEmojiPickerOpen">
                  <div class="picker_container">
                    <div class="category" v-for="category in categories" :key="`category_${category}`">
                      <span>{{ category }}</span>
                      <div class="emojis_container">
                        <a @click.prevent="handleEmojiClick(emoji)" v-for="(emoji, index) in category_emojis(category)"
                          :key="`emoji_${index}`">
                          {{ emoji }}
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="bottom_arrow" v-if="show_arrow"></div>
                </div>

                <a @click.prevent="toggleEmojiPicker"><i class="fa fa-smile"></i></a>
              </div>

              <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </div>

          </div>
        </form>

        <div id="message"></div>
      </div>

      <div id="video-row" :class="FullScreen === true ? 'VideoFullScreen' : ''">
        <div class="video-container" v-if="callPlaced">
          <video ref="userVideo" muted playsinline autoplay class="cursor-pointer"
            :class="isFocusMyself === true ? 'user-video' : 'partner-video'" @click="toggleCameraArea" />
          <video ref="partnerVideo" playsinline autoplay class="cursor-pointer"
            :class="isFocusMyself === true ? 'partner-video' : 'user-video'" @click="toggleCameraArea"
            v-if="videoCallParams.callAccepted" />
          <div class="partner-video" v-else>
            <div v-if="callPartner" class="DailingPad column items-center q-pt-xl">
              <div class="col q-gutter-y-md text-center">
                <p class="q-pt-md">
                  <strong>{{ callPartner }}</strong>
                </p>
                <p>calling...</p>
              </div>
            </div>
          </div>
          <button type="button" class="videocallerbutton FullScreen" @click="toogleFullScreen">
            <i :class="['fas', FullScreen ? 'fa-compress-alt' : 'fa-expand-alt']"></i>
          </button>
          <div class="action-btns">
            <button type="button" class="videocallerbutton Audio" @click="toggleMuteAudio">
              <i :class="['fas', mutedAudio ? 'fa-microphone-slash' : 'fa-microphone']"></i>
            </button>
            <button type="button" class="videocallerbutton Video" @click="toggleMuteVideo">
              <i :class="['fas', mutedVideo ? 'fa-video-slash' : 'fa-video']"></i>
            </button>
            <button type="button" class="videocallerbutton Callend" @click="endCall">
              <i class="fas fa-phone-alt"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Incoming Call  -->
      <div class="IncomingCallBox" v-if="incomingCallDialog">
        <div class="col">
          <p>
            Incoming Call From <strong>{{ callerDetails.name }}</strong>
          </p>
          <div class="btn-group" role="group">

            <button type="button" class="videocallerbutton Callend" @click="declineCall">
              <i class="fas fa-phone-alt"></i>
            </button>

            <button type="button" class="videocallerbutton CallAccept" @click="acceptCall">
              <i class="fas fa-phone"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- End of Incoming Call  -->

      <div class="CreateNewRoomWrap" v-if="createRoom">
        <div class="CreateNewRoom">
          <div class="roomHeader">
            <h3>Create a New Room</h3>
            <span @click="toggleCreateRoom"><i class="fa fa-times"></i></span>
          </div>
          <div class="roomContent">
            <div class="form-group mb-4">
              <label for="RoomName" class="mb-3">Room Name</label>
              <input type="text" class="form-control" id="RoomName" name="room_name"
                placeholder="What do you want to call this room?">
            </div>
            <div class="form-group mb-4 addGroupUser" style="position: relative;">
              <label for="AddPeople" class="mb-3">Add People</label>
              <div class="suggestionsWrap">
                <div class="selected-suggestions">
                  <span v-for="(selected, index) in selectedSuggestions" :key="index" class="selected-item">
                    {{ selected }}
                    <span @click="removeSelected(index)" class="remove-button"><i class="fa fa-times"></i></span>
                  </span>
                </div>
                <input v-model="inputValue" @input="handleInput" @keydown.down="highlightNext"
                  @keydown.up="highlightPrevious">
                <ul v-show="showSuggestions" class="suggestions">
                  <li v-for="(suggestion, index) in filteredSuggestions" :key="index"
                    :class="{ active: index === highlightedIndex }" @click="selectSuggestion(suggestion)">
                    {{ suggestion.name }}
                  </li>
                </ul>
              </div>
            </div>

            <div class="form-group mb-4">
              <label for="Message" class="mb-3">Message</label>
              <textarea class="form-control" id="GroupMessage" rows="5" name="Message"
                placeholder="Type your message..."></textarea>
            </div>

            <div class="roomFooter text-end">
              <button class="btn btn-link" @click="toggleCreateRoom">Cancel</button>
              <button class="btn btn-success" @click="createGroup">Create</button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div id="sidebar-layout" class="sidebar-layout">
      <div class="ProfileWrap">
        <div class="ProfileImage">
          <i class="fa fa-user"></i>
          <span class="online"></span>
        </div>
        <div class="ProfileContent" v-if="selectedUserName">
          <h3>{{ selectedUserName }}</h3>
          <h5>SaveOnDev</h5>
          <p>9:33 BD (13 h behind)</p>
          <p><i class="fa fa-briefcase"></i> Backend Developer</p>
        </div>
        <div class="ProfileContent" v-if="selectedGroupName">
          <h3>{{ selectedGroupName }}</h3>
          <h5>SaveOnDev</h5>
          <p>9:33 BD (13 h behind)</p>
          <p><i class="fa fa-briefcase"></i> Backend Developer</p>
        </div>
      </div>

      <div class="Sidebarmenu">
        <ul>
          <li>
            <a href="#">
              <i class="fa fa-search"></i>
              Search Messages
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-user"></i>
              People
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-link"></i>
              Files & Link
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-address-book"></i>
              Personal notepad
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Peer from "simple-peer";
import { getPermissions } from "../helpers";
import data from './emojis-data.json';
export default {
  props: [
    "allusers",
    "allgroups",
    "ccccc",
    "turn_url",
    "authuserid",
    "turn_username",
    "turn_credential",
    "fromusername"
  ],
  data() {
    return {
      messageElement: '',
      messageOutput: '',
      message: '',
      tags: [],
      newTag: "",
      isDropdownOpen: false,
      newMsgModal: false,
      createRoom: false,
      fromUserID: null, // Set your fromUserID
      authUserId: this.authuserid, // Set your fromUserID
      toUserID: null, // Set your toUserID
      isFocusMyself: true,
      callPlaced: false,
      callPartner: null,
      selectedUserID: null,
      selectedUserName: "",
      selectedGroupID: null,
      selectedGroupName: "",
      fromUserName: this.fromusername,
      mutedAudio: false,
      mutedVideo: false,
      FullScreen: false,
      videoCallParams: {
        users: [],
        stream: null,
        receivingCall: false,
        caller: null,
        callerSignal: null,
        callAccepted: false,
        channel: null,
        peer1: null,
        peer2: null,
      },
      isEmojiPickerOpen: false,
      inputContent: '',
      show_arrow: true,
      data: data,
      inputHasFocus: false,
      profilePicSrc: '',
      FileName: '',
      uploaded: false,
      inputValue: '',
      selectedSuggestions: [],
      selectedUserIds: [],
      // suggestions: ['User 1', 'User 2', 'User 3', 'User 4', 'Imran', 'Jone Due', 'Shajib'],
      suggestions: this.allusers,
      showSuggestions: false,
      highlightedIndex: -1,
    };
  },

  mounted() {
    this.initializeChannel(); // this initializes laravel echo
    this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
    this.privateMsgEvent();
  },
  computed: {
    // filteredSuggestions() {
    //   return this.allusers.filter(suggestion => {
    //     // console.log(suggestion);
    //     // suggestion.name.toLowerCase().includes(this.inputValue.toLowerCase());
    //     console.log(suggestion.name.toLowerCase().includes(this.inputValue.toLowerCase()));
    //   });
    // },
    filteredSuggestions() {
      return this.suggestions.filter(suggestion =>
        suggestion.name.toLowerCase().includes(this.inputValue.toLowerCase())
      );
    },
    incomingCallDialog() {
      if (
        this.videoCallParams.receivingCall &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        return true;
      }
      return false;
    },

    categories() {
      return Object.keys(data);
    },

    category_emojis: () => (category) => {
      return Object.values(data[category]);
    },

    callerDetails() {
      if (
        this.videoCallParams.caller &&
        this.videoCallParams.caller !== this.authuserid
      ) {
        const incomingCaller = this.allusers.filter(
          (user) => user.id === this.videoCallParams.caller
        );

        return {
          id: this.videoCallParams.caller,
          name: `${incomingCaller[0].name}`,
        };
      }
      return null;
    },
  },
  methods: {

    handleInput() {
      this.showSuggestions = true;
      this.highlightedIndex = -1;
    },
    highlightNext() {
      if (this.highlightedIndex < this.filteredSuggestions.length - 1) {
        this.highlightedIndex++;
      }
    },
    highlightPrevious() {
      if (this.highlightedIndex > 0) {
        this.highlightedIndex--;
      }
    },
    selectSuggestion(suggestion) {
      this.selectedUserIds.push(suggestion.id)
      this.selectedSuggestions.push(suggestion.name);
      this.inputValue = '';
      this.showSuggestions = false;
    },

    removeSelected(index) {
      this.selectedSuggestions.splice(index, 1);
    },

    addTag() {
      if (this.newTag.trim() !== "") {
        this.tags.push(this.newTag.trim());
        this.newTag = "";
      }
    },
    removeTag(index) {
      this.tags.splice(index, 1);
    },

    handleFileChange(event) {

      const input = event.target;

      if (input.files && input.files[0]) {

        console.log(input.files[0]);

        const reader = new FileReader();

        reader.onload = (e) => {
          this.profilePicSrc = e.target.result;
          this.FileName = input.files[0].name;
          this.uploaded = true;
        };
        reader.readAsDataURL(input.files[0]);
      }
    },

    clearImage() {
      this.profilePicSrc = ''; // Reset the image source
      this.uploaded = false;   // Reset the uploaded status
      this.FileName = '';
      this.$refs.fileInput.value = '';
    },

    initializeChannel() {
      this.videoCallParams.channel = window.Echo.join("presence-video-channel");
    },

    toggleCreateRoom() {
      this.createRoom = !this.createRoom;
      this.newMsgModal = false;
    },
    createGroup() {
      const roomName = document.getElementById("RoomName");
      const groupMessage = document.getElementById("GroupMessage");
      // console.log(roomName.value);
      // console.log(groupMessage.value);
      console.log(this.selectedUserIds);
      // if (roomName.value && this.selectedUserIds.length) {
      //   axios
      //     .post('/create-group', {
      //       group_name: roomName.value,
      //       groupMembers: this.authuserid,
      //     })
      //     .then((res) => {
      //       console.log("res", res);
      //       if (res.config.data !== undefined && res.config.data != null) {
      //         let msgs = JSON.parse(res.config.data);
      //         let html = "";
      //         html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
      //         this.messageOutput += html;
      //       }
      //     })
      //     .catch((error) => {
      //       console.error(error);
      //     });

      //   this.inputContent = '';
      // }
    },

    OpenOptionMenu() {
      this.isDropdownOpen = !this.isDropdownOpen;
      if (this.isDropdownOpen === true) {
        console.log('Open');
        document.addEventListener('click', this.clickOutsideDropdown);
      } else {
        console.log('Close');
        document.removeEventListener('click', this.clickOutsideDropdown);
      }
    },
    toggleNewMsg() {
      this.newMsgModal = !this.newMsgModal;
      this.createRoom = false;
    },
    toggleEmojiPicker() {
      this.inputHasFocus = true;
      this.isEmojiPickerOpen = !this.isEmojiPickerOpen;
      if (this.isEmojiPickerOpen) {
        console.log('Open ...##');
        //this.setupClickOutsideListener();
        document.addEventListener('click', this.clickOutsideHandler);
      } else {
        console.log('Close ..## ');
        //this.removeClickOutsideListener();
        document.removeEventListener('click', this.clickOutsideHandler);
      }
    },
    handleEmojiClick(emoji) {
      if (this.inputHasFocus) {
        const inputField = this.$refs.inputField;
        const startPos = inputField.selectionStart;
        const endPos = inputField.selectionEnd;
        this.inputContent = this.inputContent.substring(0, startPos) + emoji + this.inputContent.substring(endPos);
        const newCursorPosition = startPos + emoji.length;
        this.$nextTick(() => {
          inputField.setSelectionRange(newCursorPosition, newCursorPosition);
          inputField.focus();
        });
      } else {
        this.inputContent += emoji;
      }
    },
    handleInputFocus() {
      this.inputHasFocus = true;
    },
    handleInputBlur() {
      this.inputHasFocus = false;
    },
    setupClickOutsideListener() {
      document.addEventListener('click', this.clickOutsideHandler);
    },
    removeClickOutsideListener() {
      document.removeEventListener('click', this.clickOutsideHandler);
    },

    clickOutsideHandler(event) {
      const emojiPicker = this.$refs.emojiPicker;

      console.log(emojiPicker);

      if (emojiPicker && !emojiPicker.contains(event.target)) {
        this.isEmojiPickerOpen = false;
        this.removeClickOutsideListener();
      }
    },

    clickOutsideDropdown(event) {
      const DropdownRefs = this.$refs.DropdownRefs;
      //console.log(DropdownRefs);
      if (DropdownRefs && !DropdownRefs.contains(event.target)) {
        this.isDropdownOpen = false;
        this.removeClickOutsideListener();
      }
    },

    privateMsgEvent() {
      window.Echo.channel("private-laravelChat").listen(".chatting", (res) => {
        if (res !== undefined && res != null) {
          let html = "";
          res.messageHistory.forEach((itm) => {
            html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + itm.from_user_details + ' <span>' + itm.message_time + '</span></h3><p>' + itm.message + '</p></div></div>';
          });
          this.messageOutput = html;
        }
      });
    },
    getMediaPermission() {
      return getPermissions()
        .then((stream) => {
          this.videoCallParams.stream = stream;
          if (this.$refs.userVideo) {
            this.$refs.userVideo.srcObject = stream;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    initializeCallListeners() {
      this.videoCallParams.channel.here((users) => {
        this.videoCallParams.users = users;
      });

      this.videoCallParams.channel.joining((user) => {
        // check user availability
        const joiningUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        if (joiningUserIndex < 0) {
          this.videoCallParams.users.push(user);
        }
      });

      this.videoCallParams.channel.leaving((user) => {
        const leavingUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        this.videoCallParams.users.splice(leavingUserIndex, 1);
      });
      // listen to incomming call
      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        if (data.type === "incomingCall") {
          // add a new line to the sdp to take care of error
          const updatedSignal = {
            ...data.signalData,
            sdp: `${data.signalData.sdp}\n`,
          };

          this.videoCallParams.receivingCall = true;
          this.videoCallParams.caller = data.from;
          this.videoCallParams.callerSignal = updatedSignal;
        }
      });
    },
    placeGroupDataOnUserClick(id, name) {
      this.selectedGroupID = id;
      this.selectedGroupName = name;
      this.selectedUserName = "";
      axios.get("/group-msg-history/" + this.selectedGroupID, {
      })
        .then((res) => {
          console.log(res);
          if (res.data.data !== undefined && res.data.data != null) {
            let html = "";
            res.data.data.forEach((itm) => {
              html += '<div class="MsgTitme"><span>Sat 11:29 PM</span></div><div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + itm.from_user_details + ' <span>' + itm.message_time + '</span></h3><p>' + itm.message + '</p></div></div>';
            });
            this.messageOutput = html;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    placeUserClick(id, name) {
      this.selectedUserID = id;
      this.selectedUserName = name;
      this.newMsgModal = false;
      axios.get("/msg-history/" + this.authUserId + "/" + id, {
      })
        .then((res) => {
          if (res.data.data !== undefined && res.data.data != null) {
            let html = "";
            res.data.data.forEach((itm) => {
              html += '<div class="MsgTitme"><span>Sat 11:29 PM</span></div><div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + itm.from_user_details + ' <span>' + itm.message_time + '</span></h3><p>' + itm.message + '</p></div></div>';
            });
            this.messageOutput = html;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async placeVideoCall(id, name) {
      this.callPlaced = true;
      this.callPartner = name;
      await this.getMediaPermission();
      this.videoCallParams.peer1 = new Peer({
        initiator: true,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [
            {
              urls: this.turn_url,
              username: this.turn_username,
              credential: this.turn_credential,
            },
          ],
        },
      });

      this.videoCallParams.peer1.on("signal", (data) => {
        // send user call signal
        axios
          .post("/video/call-user", {
            user_to_call: id,
            signal_data: data,
            from: this.authuserid,
          })
          .then(() => { })
          .catch((error) => {
            console.log(error);
          });
      });

      this.videoCallParams.peer1.on("stream", (stream) => {
        console.log("call streaming");
        if (this.$refs.partnerVideo) {
          this.$refs.partnerVideo.srcObject = stream;
        }
      });

      this.videoCallParams.peer1.on("connect", () => {
        console.log("peer connected");
      });

      this.videoCallParams.peer1.on("error", (err) => {
        console.log(err);
      });

      this.videoCallParams.peer1.on("close", () => {
        console.log("call closed caller");
      });

      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        if (data.type === "callAccepted") {
          if (data.signal.renegotiate) {
            console.log("renegotating");
          }
          if (data.signal.sdp) {
            this.videoCallParams.callAccepted = true;
            const updatedSignal = {
              ...data.signal,
              sdp: `${data.signal.sdp}\n`,
            };
            this.videoCallParams.peer1.signal(updatedSignal);
          }
        }
      });
    },
    listenForNewMessages() {
      window.Echo.channel('private-laravelChat').listen('.chatting', (res) => {
        let userName = 'anonymous';
        if (res.userName != null && res.userName != '') {
          userName = res.userName;
        }
        const message = {
          id: Date.now(), // Use a unique identifier for the key
          username: userName,
          message: window.emoji.replace_colons(res.message),
        };
        this.messages.push(message);
      });
    },

    async acceptCall() {
      this.callPlaced = true;
      this.videoCallParams.callAccepted = true;
      await this.getMediaPermission();
      this.videoCallParams.peer2 = new Peer({
        initiator: false,
        trickle: false,
        stream: this.videoCallParams.stream,
        config: {
          iceServers: [
            {
              urls: this.turn_url,
              username: this.turn_username,
              credential: this.turn_credential,
            },
          ],
        },
      });
      this.videoCallParams.receivingCall = false;
      this.videoCallParams.peer2.on("signal", (data) => {
        axios
          .post("/video/accept-call", {
            signal: data,
            to: this.videoCallParams.caller,
          })
          .then(() => { })
          .catch((error) => {
            console.log(error);
          });
      });

      this.videoCallParams.peer2.on("stream", (stream) => {
        this.videoCallParams.callAccepted = true;
        this.$refs.partnerVideo.srcObject = stream;
      });

      this.videoCallParams.peer2.on("connect", () => {
        console.log("peer connected");
        this.videoCallParams.callAccepted = true;
      });

      this.videoCallParams.peer2.on("error", (err) => {
        console.log(err);
      });

      this.videoCallParams.peer2.on("close", () => {
        console.log("call closed accepter");
      });

      this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);
    },
    toggleCameraArea() {
      if (this.videoCallParams.callAccepted) {
        this.isFocusMyself = !this.isFocusMyself;
      }
    },
    getUserOnlineStatus(id) {
      const onlineUserIndex = this.videoCallParams.users.findIndex(
        (data) => data.id === id
      );
      if (onlineUserIndex < 0) {
        //return "Offline";
        return "<span style='color: gray;'>Offline</span>";
      }
      //return "Online";
      return "<span style='color: green;'>Online</span>";
    },
    declineCall() {
      this.videoCallParams.receivingCall = false;
    },

    toggleMuteAudio() {
      if (this.mutedAudio) {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = true;
        this.mutedAudio = false;
      } else {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = false;
        this.mutedAudio = true;
      }
    },

    toogleFullScreen() {
      if (this.FullScreen) {
        this.FullScreen = false;
      } else {
        this.FullScreen = true;
      }
    },

    toggleMuteVideo() {
      if (this.mutedVideo) {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = true;
        this.mutedVideo = false;
      } else {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
        this.mutedVideo = true;
      }
    },
    dateFormat(date) {
      //get hours, minutes, and seconds
      let hours = date.getHours();
      let minutes = date.getMinutes();
      let seconds = date.getSeconds();

      // convert hours to 12-hour format
      hours = hours % 12;
      hours = hours ? hours : 12;

      // add leading zeros to minutes and seconds
      minutes = minutes < 10 ? '0' + minutes : minutes;
      seconds = seconds < 10 ? '0' + seconds : seconds;

      // add am/pm suffix
      const amPm = hours >= 12 ? 'PM' : 'AM';

      // combine all parts into a time string
      const timeString = hours + ':' + minutes + ':' + seconds + ' ' + amPm;

      return timeString;
    },
    stopStreamedVideo(videoElem) {
      const stream = videoElem.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach((track) => {
        track.stop();
      });
      videoElem.srcObject = null;
    },
    sendGroupMessage() {
      if (this.inputContent !== '' && this.selectedGroupID != '') {
        axios
          .post('/send-group-message', {
            message: this.inputContent,
            groupID: this.selectedGroupID,
            memberID: this.authuserid,
          })
          .then((res) => {
            console.log("res", res);
            if (res.config.data !== undefined && res.config.data != null) {
              let msgs = JSON.parse(res.config.data);
              let html = "";
              html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
              this.messageOutput += html;
            }
          })
          .catch((error) => {
            console.error(error);
          });

        this.inputContent = '';
      }
    },
    sendMessage() {
      if (this.inputContent !== '' && this.selectedUserID != '') {
        axios
          .post('/send-message', {
            message: this.inputContent,
            fromUserID: this.authuserid,
            toUserID: this.selectedUserID,
          })
          .then((res) => {
            console.log("res", res);
            if (res.config.data !== undefined && res.config.data != null) {
              let msgs = JSON.parse(res.config.data);
              let html = "";
              html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
              this.messageOutput += html;
            }
          })
          .catch((error) => {
            console.error(error);
          });

        this.inputContent = '';
      }
    },
    uploadFile() {
      const file = this.$refs.fileInput.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('file', file);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        axios
          .post('/upload', formData, {
            headers: {
              'X-CSRF-TOKEN': csrfToken,
            },
          })
          .then((response) => {
            axios
              .post('/send-message', {
                message: `<a target="_blank" href="${response.data.url}"><img width="100" src="/storage/uploads/free-thumb.png"></a><br><span>${response.data.message}</span>`,
                fromUserID: this.authuserid,
                toUserID: this.selectedUserID,
              })
              .then((res) => {
                console.log("res", res);
                if (res.config.data !== undefined && res.config.data != null) {
                  let msgs = JSON.parse(res.config.data);
                  let html = "";
                  html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
                  this.messageOutput += html;
                }
                // Listen for new chat messages using Echo or your preferred method
              })
              .catch((error) => {
                console.error(error);
              });

            this.message = '';
          })
          .catch((error) => {
            this.message = 'Error uploading file.';
            console.error(error);
          });
      } else {
        this.message = 'Please select a file.';
      }
    },
    endCall() {
      // if video or audio is muted, enable it so that the stopStreamedVideo method will work
      if (!this.mutedVideo) this.toggleMuteVideo();
      if (!this.mutedAudio) this.toggleMuteAudio();
      this.stopStreamedVideo(this.$refs.userVideo);
      if (this.authuserid === this.videoCallParams.caller) {
        this.videoCallParams.peer1.destroy();
      } else {
        this.videoCallParams.peer2.destroy();
      }
      this.videoCallParams.channel.pusher.channels.channels[
        "presence-presence-video-channel"
      ].disconnect();

      setTimeout(() => {
        this.callPlaced = false;
      }, 3000);
    },
  },
};
</script>

<style scoped> .newUserMsg,
 .suggestionsWrap {
   display: flex;
   flex-direction: row;
   align-items: center;
 }

 .newUserMsg {
   position: absolute;
   z-index: 9999;
   background: #fff;
   width: 90%;
   top: 1px;
 }

 .newUserMsg input {
   border: none;
   height: 48px;
 }

 .newUserMsg input:focus {
   outline: none;
 }

 .suggestions {
   list-style-type: none;
   padding: 0;
   margin: 0;
   border: 1px solid #ccc;
   max-height: 150px;
   overflow-y: auto;
   position: absolute;
   top: 40px;
   min-width: 220px;
   z-index: 99;
   background: #fff;
 }

 .suggestions li {
   padding: 5px;
   cursor: pointer;
   border: 1px solid #ccc;
 }

 .suggestions li.active {
   background-color: #f0f0f0;
 }

 .selected-item {
   background-color: #f9f9f9;
   border: 1px solid #ccc;
   padding: 2px 0px 2px 10px;
   border-radius: 7px;
   margin-right: 10px;
 }

 .selected-item span.remove-button {
   background: #EB5347;
   color: #fff;
   height: 20px;
   width: 20px;
   text-align: center;
   display: inline-block;
   line-height: normal;
   border-radius: 10px;
   padding: 1px 0;
   margin-left: 5px;
 }

 .addGroupUser {
   position: relative;
 }

 .addGroupUser .suggestionsWrap {
   border: 1px solid #ccc;
   height: 40px;
   border-radius: 6px;
   padding: 0px 10px;
   position: relative;
 }

 .addGroupUser .suggestionsWrap input {
   border: none;
 }

 .addGroupUser .suggestionsWrap input:focus {
   outline: none;
 }

 .tag-container {
   display: flex;
   flex-wrap: wrap;
 }

 .tag {
   display: flex;
   align-items: center;
   background-color: #f2f2f2;
   border: 1px solid #ccc;
   border-radius: 10px;
   padding: 2px 10px;
   margin-right: 5px;
   margin-top: 10px;
 }

 .tag-remove {
   margin-left: 8px;
   cursor: pointer;
 }

 /* End */

 .CreateNewRoomWrap {
   position: fixed;
   height: 100%;
   width: 100%;
   background: rgba(0, 0, 0, 0.5);
   left: 0;
   top: 0;
   z-index: 999;
 }

 .CreateNewRoom {
   position: fixed;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   width: 750px;
   background: #fff;
   padding: 20px 25px 30px;
   border-radius: 10px;
   max-height: 80vh;
   overflow: auto;
 }

 .roomHeader {
   display: flex;
   flex-direction: row;
   justify-content: space-between;
   margin-bottom: 25px;
 }

 .roomHeader h3 {
   font-size: 22px;
   margin-bottom: 0px;
 }

 .roomHeader span {
   cursor: pointer;
 }

 .roomFooter button.btn-link {
   color: #108a00;
   text-decoration: none;
 }

 .roomFooter button.btn-success {
   border-radius: 50px;
   background-color: #108a00;
   width: 120px;
 }

 .roomFooter button.btn-link:hover {
   color: #14a800;
   text-decoration: underline;
 }

 .roomFooter button.btn-success:hover {
   background-color: #14a800;
 }

 #video-row {
   width: 700px;
   max-width: 90vw;
   position: absolute;
   /* top: 0px;
        right: 0px */
   top: calc(50% - 30px);
   left: 50%;
   z-index: 99999;
   transform: translate(-50%, -50%);
 }

 #video-row.VideoFullScreen {
   width: 100vw;
   max-width: 100vw;
   z-index: 9999;
   position: fixed;
   top: 0;
   transform: inherit;
   left: 0;
 }

 #video-row.VideoFullScreen .video-container {
   width: 100vw;
   max-width: 100vw;
   height: 100vh;
   max-height: 100vh
 }

 #incoming-call-card {
   border: 1px solid #0acf83;
 }

 .video-container {
   /* width: 700px;
        height: 500px;
        max-width: 90vw;
        max-height: 50vh;  */
   position: relative;
   box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
   background-color: #fff;
   border-radius: 10px;
 }

 .video-container .user-video {
   width: 30%;
   position: absolute;
   left: auto;
   top: 10px;
   right: 10px;
   border-radius: 6px;
   z-index: 2;
   border-radius: 10px;
 }

 .video-container .partner-video {
   width: 100%;
   height: 100%;
   position: absolute;
   left: 0;
   right: 0;
   bottom: 0;
   top: 0;
   z-index: 1;
   margin: 0;
   padding: 0;
   border-radius: 10px;
   /* background-color: #000; */
 }

 .VideoFullScreen .video-container .partner-video {
   /* border-radius: 0px; */
 }

 .video-container .action-btns {
   position: absolute;
   bottom: 20px;
   left: 50%;
   margin-left: 0px;
   z-index: 3;
   display: flex;
   flex-direction: row;
   transform: translate(-50%, 0px);
 }

 /*
## Emoji CSS Start
****************/
 .emoji_picker {
   position: absolute;
   display: flex;
   flex-direction: column;
   width: 20rem;
   height: 20rem;
   max-width: 100%;
   bottom: 55px;
   right: 0px;
 }

 .emoji_picker,
 .bottom_arrow {
   box-shadow: 0 0 5px 1px rgba(0, 0, 0, .0975);
 }

 .emoji_picker,
 .picker_container {
   border-radius: 0.5rem;
   background: white;
 }

 .picker_container {
   position: relative;
   padding: 1rem;
   overflow: auto;
   z-index: 1;
 }

 .category {
   display: flex;
   flex-direction: column;
   margin-bottom: 1rem;
   color: rgb(169, 169, 169);
 }

 .emojis_container {
   display: flex;
   flex-wrap: wrap;
 }

 .category button {
   margin: 0.5rem;
   margin-left: 0;
   background: inherit;
   border: none;
   font-size: 1.75rem;
   padding: 0;
 }

 .bottom_arrow {
   position: absolute;
   left: 82%;
   bottom: 0;
   width: 0.75rem;
   height: 0.75rem;
   transform: translate(-82%, 50%) rotate(45deg);
   background: white;
 }



 /*
## Emoji CSS End
****************/

 /*
## Upload CSS Start
********************/
 .PreviewWrap {
   bottom: 80px;
   position: absolute;
   box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
   text-align: left;
   padding: 6px;
   border-radius: 8px;
   width: auto;
   left: 10px;
   background-color: #fff;
   /* width: calc(25% - 22px); 
    transform: translate(-50%, 0px);
    left: 50%; */
 }

 .profile_pic {
   display: inline-block;
   opacity: 0;
 }

 .AttachmentFile .profile_pic.uploaded {
   width: 50px;
   height: 50px;
   opacity: 1;
   object-fit: cover;
 }

 .file-upload {
   /* display: none; */
   opacity: 0;
   width: 25px;
   position: absolute;
   left: 0;
 }

 .AttachmentFile {
   width: 25px;
   margin-right: 10px;
   text-align: center;
 }

 .p-image {
   position: relative;
   color: #666666;
   transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
 }

 .p-image:hover {
   transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
 }

 .upload-button {
   font-size: 1.2em;
   cursor: pointer;
 }

 .upload-button:hover {
   transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
   color: #999;
 }

 .UploadFile {
   display: flex;
   flex-direction: row;
   align-items: center;
   position: relative;
 }

 .UploadFile p {
   margin: 0px 15px;
   font-size: 12px;
 }

 .UploadFile span {
   position: absolute;
   right: -10px;
   top: -15px;
   background: #fff;
   width: 20px;
   height: 20px;
   text-align: center;
   border-radius: 50%;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
   color: rgb(183, 42, 42);
   font-size: 13px;
   padding: 1px 0;
   cursor: pointer;
 }

 /*
## Upload CSS End
********************/

 /* Mobiel Styles */
 @media only screen and (max-width: 768px) {
   .video-container {
     height: 50vh;
   }
 }
</style>